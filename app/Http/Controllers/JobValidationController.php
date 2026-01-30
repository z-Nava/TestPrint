<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

class JobValidationController extends Controller
{
    /**
     * Ruta al Excel.
     * OJO: "X:\" es unidad mapeada; puede fallar si PHP corre como servicio.
     * En servidor suele ser mejor una ruta UNC: \\server\share\...\Job_Validation_Zaira.xlsx
     */
private string $filePath = '\\\\met.globaltti.net\\milwaukeetool\\Torreon\\HOJA MASTER SCANNER\\Jobs_Released_Zaira.xlsx';
//private string $filePath = 'C:/Users/navar/Desktop/INFO CUARTO DE ETIQUETAS/Job_Released_Zaira.xlsx';
    /**
     * Columnas requeridas (tal como vienen en el archivo)
     */
    private array $wantedColumns = [
        'JOB_NUMBER',
        'LINE',
        'JOB_STATUS',
        'ASSEMBLY',
        'PART_DESCRIPTION',
        'JOB_QTY',
        'TTL_CUST_PO',
        'SHIP_CODE'
    ];

    public function index(Request $request)
    {
        $cacheKey = 'job_validation_table_v1';

        $data = Cache::remember($cacheKey, now()->addMinutes(5), function () {
            Log::info('JobValidation: cache miss, leyendo Excel...');
            return $this->readExcel();
        });

        $rows = $data['rows'] ?? [];
        $jobFilter = trim((string)$request->query('job', ''));

        if ($jobFilter !== '') {
            $rows = array_values(array_filter($rows, function ($r) use ($jobFilter) {
                return stripos((string)($r['JOB_NO'] ?? ''), $jobFilter) !== false;
            }));
        }

        // Limita para que no se muera el navegador
        $rows = array_slice($rows, 0, 200);

        return view('job-validation', [
            'columns'   => $data['columns'] ?? [],
            'rows'      => $rows,
            'debug'     => $data['debug'] ?? null,
            'error'     => $data['error'] ?? null,
            'source'    => $this->filePath,
            'cached_at' => Cache::get('job_validation_cached_at'),
            'jobFilter' => $jobFilter,
        ]);
    }


    public function reload()
    {
        $cacheKey = 'job_validation_table_v1';

        Cache::forget($cacheKey);
        Cache::forget('job_validation_cached_at');

        Log::info('JobValidation: reload solicitado, limpiando cache y releyendo Excel...');

        // Forzamos recarga inmediata para validar
        $data = $this->readExcel();

        Cache::put($cacheKey, $data, now()->addMinutes(5));
        Cache::put('job_validation_cached_at', now()->toDateTimeString(), now()->addMinutes(5));

        return redirect()
            ->route('job.validation')
            ->with('ok', 'Datos recargados desde el Excel.');
    }

    /**
     * Normaliza encabezados: trim, colapsa espacios, minúsculas.
     */
    private function norm(string $s): string
    {
        $s = preg_replace('/\s+/', ' ', $s);
        return mb_strtolower(trim((string)$s));
    }

    /**
     * Lee el XLSX, detecta la fila de encabezados, filtra columnas necesarias y arma rows.
     */
    private function readExcel(): array
    {
        $startedAt = microtime(true);

        Log::info('JobValidation: iniciando lectura', [
            'filePath' => $this->filePath,
        ]);

        if (!file_exists($this->filePath)) {
            Log::error('JobValidation: archivo no encontrado', [
                'filePath' => $this->filePath,
            ]);

            return [
                'columns' => [],
                'rows' => [],
                'error' => "No se encontró el archivo: {$this->filePath}",
                'debug' => [
                    'tip' => 'Si usas X:\, intenta usar ruta UNC (\\\\server\\\\share\\\\...). Las unidades mapeadas pueden no existir para el usuario del servidor web.',
                ],
            ];
        }

        try {
            $spreadsheet = IOFactory::load($this->filePath);
            $sheetNames  = $spreadsheet->getSheetNames();

            // Usamos la primera hoja (suele ser lo más confiable)
            $sheet = $spreadsheet->getSheet(0);

            Log::info('JobValidation: hojas detectadas', [
                'sheetNames' => $sheetNames,
                'usingSheet' => $sheet->getTitle(),
            ]);

            /**
             * 1) Tomamos un bloque inicial para detectar la fila de encabezados
             * (primeras 50 filas, columnas A..AD)
             */
            $probe = $sheet->rangeToArray('A1:AD50', null, true, true, true);

            // 2) Detecta fila de encabezados: la fila que contenga más coincidencias con wantedColumns
            $wantedNorm = array_map(fn($c) => $this->norm($c), $this->wantedColumns);

            $bestRowIndex = null;
            $bestHits = 0;
            $bestHeaders = [];

            foreach ($probe as $rowIndex => $row) {
                // Construimos headers "normalizados" de la fila
                $headers = [];
                foreach ($row as $colLetter => $value) {
                    $h = $this->norm((string)$value);
                    if ($h !== '') {
                        $headers[$h] = $colLetter;
                    }
                }

                // Contamos hits contra wanted
                $hits = 0;
                foreach ($wantedNorm as $w) {
                    if (isset($headers[$w])) $hits++;
                }

                if ($hits > $bestHits) {
                    $bestHits = $hits;
                    $bestRowIndex = $rowIndex;
                    $bestHeaders = $headers;
                }
            }

            Log::info('JobValidation: detección de encabezados', [
                'bestRowIndex' => $bestRowIndex,
                'bestHits' => $bestHits,
                'wantedColumns' => $this->wantedColumns,
            ]);

            // Si no encontró al menos 2 headers esperados, devolvemos debug
            if ($bestRowIndex === null || $bestHits < 2) {
                $availableHeaders = array_keys($bestHeaders);

                Log::warning('JobValidation: no se pudieron detectar headers suficientes', [
                    'bestRowIndex' => $bestRowIndex,
                    'bestHits' => $bestHits,
                    'availableHeadersSample' => array_slice($availableHeaders, 0, 30),
                ]);

                return [
                    'columns' => [],
                    'rows' => [],
                    'error' => 'No se pudieron detectar las columnas requeridas en el Excel.',
                    'debug' => [
                        'usingSheet' => $sheet->getTitle(),
                        'header_row_detected' => $bestRowIndex,
                        'hits_detected' => $bestHits,
                        'wanted_columns' => $this->wantedColumns,
                        'available_headers_sample' => array_slice($availableHeaders, 0, 60),
                        'tip' => 'Verifica si el Excel trae encabezados distintos o si están en otra hoja.',
                    ],
                ];
            }

            // 3) Mapear columnas requeridas a letras
            $finalColumns = [];
            $colLetters = [];

            foreach ($this->wantedColumns as $colName) {
                $key = $this->norm($colName);
                if (isset($bestHeaders[$key])) {
                    $finalColumns[] = $colName; // mostramos el nombre original
                    $colLetters[$colName] = $bestHeaders[$key]; // letra: A, B, C...
                }
            }

            if (empty($finalColumns)) {
                Log::warning('JobValidation: headers detectados pero sin match final', [
                    'bestRowIndex' => $bestRowIndex,
                    'bestHeaders' => array_keys($bestHeaders),
                ]);

                return [
                    'columns' => [],
                    'rows' => [],
                    'error' => 'Se detectaron encabezados, pero no coincidieron con las columnas requeridas.',
                    'debug' => [
                        'usingSheet' => $sheet->getTitle(),
                        'header_row_detected' => $bestRowIndex,
                        'wanted_columns' => $this->wantedColumns,
                        'available_headers_sample' => array_slice(array_keys($bestHeaders), 0, 60),
                    ],
                ];
            }

            Log::info('JobValidation: columnas mapeadas', [
                'mappedColumns' => $colLetters,
            ]);

            /**
             * 4) Leer desde la fila de encabezados hasta el final
             *    (A{headerRow} : {lastCol}{lastRow})
             */
            $highestRow = $sheet->getHighestRow();
            $highestCol = $sheet->getHighestColumn();

            Log::info('JobValidation: rango detectado', [
                'highestRow' => $highestRow,
                'highestCol' => $highestCol,
            ]);

            $all = $sheet->rangeToArray(
                "A{$bestRowIndex}:{$highestCol}{$highestRow}",
                null,
                true,
                true,
                true
            );

            // Datos empiezan en la fila siguiente a encabezados
            $startDataRow = $bestRowIndex + 1;

            $rows = [];
            $emptyRows = 0;

            for ($r = $startDataRow; $r <= $highestRow; $r++) {
                $row = $all[$r] ?? null;
                if (!$row) continue;

                // Saltar fila vacía
                $isEmpty = true;
                foreach ($row as $v) {
                    if (trim((string)$v) !== '') { $isEmpty = false; break; }
                }
                if ($isEmpty) { $emptyRows++; continue; }

                $out = [];
                foreach ($finalColumns as $colName) {
                    $letter = $colLetters[$colName];
                    $out[$colName] = $row[$letter] ?? '';
                }

                $rows[] = $out;
            }

            $elapsedMs = (int) ((microtime(true) - $startedAt) * 1000);

            Log::info('JobValidation: lectura completada', [
                'rows' => count($rows),
                'emptyRowsSkipped' => $emptyRows,
                'elapsedMs' => $elapsedMs,
            ]);

            Cache::put('job_validation_cached_at', now()->toDateTimeString(), now()->addMinutes(5));

            return [
                'columns' => $finalColumns,
                'rows' => $rows,
                'debug' => [
                    'usingSheet' => $sheet->getTitle(),
                    'header_row_detected' => $bestRowIndex,
                    'mapped_columns' => $colLetters,
                    'rows_count' => count($rows),
                ],
            ];
        } catch (\Throwable $e) {
            Log::error('JobValidation: excepción leyendo Excel', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return [
                'columns' => [],
                'rows' => [],
                'error' => 'Error leyendo el Excel (revisa logs).',
                'debug' => [
                    'exception' => $e->getMessage(),
                ],
            ];
        }
    }
}

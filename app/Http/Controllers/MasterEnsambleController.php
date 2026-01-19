<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class MasterEnsambleController extends Controller
{
    private string $templatePath = '';

    public function __construct()
    {
        $this->templatePath = storage_path('app/templates/master_ensamble.xlsx');
    }

    public function index()
    {
        return view('master-ensamble-form');
    }

    public function pdf(Request $request)
    {
        $data = $request->validate([
            'job_number' => ['required', 'string', 'max:30'],
        ]);

        $job = trim((string)$data['job_number']);

        Log::info('MasterEnsamble: generando PDF', [
            'job_number' => $job,
            'templatePath' => $this->templatePath,
        ]);

        if (!file_exists($this->templatePath)) {
            Log::error('MasterEnsamble: plantilla no encontrada', [
                'templatePath' => $this->templatePath,
            ]);
            return back()->with('error', 'No se encontró la plantilla master_ensamble.xlsx en storage/app/templates/');
        }

        /**
         * 1) Buscar JOB en el cache generado por JobValidationController
         */
        $cacheKey = 'job_validation_table_v1';
        $cached = Cache::get($cacheKey);

        $line = '';
        $jobRow = null;

        if (is_array($cached) && isset($cached['rows']) && is_array($cached['rows'])) {
            foreach ($cached['rows'] as $row) {
                $rowJob = trim((string)($row['JOB_NUMBER'] ?? ''));
                if ($rowJob === $job) {
                    $jobRow = $row;
                    $line = trim((string)($row['LINE'] ?? ''));
                    break;
                }
            }
        } else {
            Log::warning('MasterEnsamble: cache vacío o formato inesperado', [
                'cacheKey' => $cacheKey,
                'type' => gettype($cached),
            ]);
        }

        if (!$jobRow) {
            Log::warning('MasterEnsamble: JOB no encontrada en cache', [
                'job_number' => $job,
                'cacheKey' => $cacheKey,
            ]);
            // seguimos, pero la línea saldrá vacía
        } else {
            Log::info('MasterEnsamble: datos encontrados', [
                'job_number' => $job,
                'line' => $line,
                'assembly' => $jobRow['ASSEMBLY'] ?? null,
                'status' => $jobRow['JOB_STATUS'] ?? null,
                'qty' => $jobRow['JOB_QTY'] ?? null,
                'ttl_cust_po' => $jobRow['TTL_CUST_PO'] ?? null,
                'ship_code' => $jobRow['SHIP_CODE'] ?? null,
            ]);
        }

        /**
         * 2) Cargar plantilla XLSX
         */
        $spreadsheet = IOFactory::load($this->templatePath);
        $sheet = $spreadsheet->getSheet(0);

        /**
         * 3) Setup de página
         */
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_LETTER);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(1);

        /**
         * 4) Escribir valores (top-left de celdas combinadas)
         * - JOB -> J3 (confirmado por ti)
         * - LINE -> B5 (confirmado por ti)
         */
        $sheet->setCellValue('J3', $job);
        $sheet->setCellValue('B5', $line);

        // Si luego quieres llenar otros campos, aquí los agregamos:
        // $sheet->setCellValue('XXXX', (string)($jobRow['ASSEMBLY'] ?? ''));
        // $sheet->setCellValue('YYYY', (string)($jobRow['JOB_QTY'] ?? ''));

        /**
         * 5) Generar PDF con Writer Mpdf
         */
        $tmpDir = storage_path('app/tmp');
        if (!is_dir($tmpDir)) {
            @mkdir($tmpDir, 0777, true);
        }

        $safeJob = preg_replace('/\W+/', '_', $job);
        $pdfPath = $tmpDir . DIRECTORY_SEPARATOR . 'master_ensamble_' . $safeJob . '_' . now()->format('Ymd_His') . '.pdf';

        $writer = IOFactory::createWriter($spreadsheet, 'Mpdf');
        $writer->save($pdfPath);

        Log::info('MasterEnsamble: PDF generado', [
            'pdfPath' => $pdfPath,
        ]);

        return response()->file($pdfPath, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PrintDemoController extends Controller
{
    public function index()
    {
        return view('print-demo');
    }

    public function print(Request $request)
    {
        $data = $request->validate([
            'job' => ['required', 'string', 'max:50'],
            'fg' => ['required', 'string', 'max:50'],
            'line' => ['required', 'string', 'max:50'],
            'qty' => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        // Simulación de valores típicos de etiqueta
        $job = $data['job'];
        $fg = $data['fg'];
        $line = $data['line'];
        $qty = (int) $data['qty'];

        // Aquí construirías tu QR (ejemplo simple)
        // Normalmente el QR contiene una cadena con Job/FG/Lot/Serial, etc.
        $baseQr = "JOB={$job}|FG={$fg}|LINE={$line}";

        // Generamos CSV para BarTender: 1 fila por etiqueta (qty)
        $rows = [];
        $rows[] = ['job', 'fg', 'line', 'qr_value', 'seq'];

        for ($i = 1; $i <= $qty; $i++) {
            $rows[] = [$job, $fg, $line, $baseQr, $i];
        }

        $csv = $this->toCsv($rows);

        $filename = 'print_job_' . Str::slug($job) . '_' . now()->format('Ymd_His') . '.csv';

        return response($csv)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    private function toCsv(array $rows): string
    {
        $fh = fopen('php://temp', 'r+');

        foreach ($rows as $row) {
            fputcsv($fh, $row);
        }

        rewind($fh);
        $csv = stream_get_contents($fh);
        fclose($fh);

        return $csv ?: '';
    }

    public function printBrowser(Request $request)
    {
        $data = $request->validate([
            'job' => 'required|string|max:50',
            'fg' => 'required|string|max:50',
            'line' => 'required|string|max:50',
            'qty' => 'required|integer|min:1|max:100',
        ]);

        return view('print-browser', $data);
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DummyQRTestController extends Controller
{
    public function index()
    {
        return view('dummyqrtest');
    }

    public function zpl(Request $request)
    {
        $data = $request->validate([
            'fg' => ['required', 'string', 'max:40'],
            'job' => ['required', 'string', 'max:80'],
            'consecutivo' => ['required', 'string', 'max:30'],

            // Tamaño etiqueta (dots)
            'pw' => ['nullable', 'integer', 'min:100', 'max:2000'],
            'll' => ['nullable', 'integer', 'min:100', 'max:2000'],

            // Coordenadas (dots) — ajustables
            'fg_x' => ['nullable', 'integer', 'min:0', 'max:2000'],
            'fg_y' => ['nullable', 'integer', 'min:0', 'max:2000'],

            'job_x' => ['nullable', 'integer', 'min:0', 'max:2000'],
            'job_y' => ['nullable', 'integer', 'min:0', 'max:2000'],

            'cons_x' => ['nullable', 'integer', 'min:0', 'max:2000'],
            'cons_y' => ['nullable', 'integer', 'min:0', 'max:2000'],

            'qr_x' => ['nullable', 'integer', 'min:0', 'max:2000'],
            'qr_y' => ['nullable', 'integer', 'min:0', 'max:2000'],
            'qr_m' => ['nullable', 'integer', 'min:1', 'max:10'],
            'qr_model' => ['nullable', 'integer', 'in:1,2'],
            'qr_ecc' => ['nullable', 'string', 'in:L,M,Q,H'],
        ]);

        $fg = trim($data['fg']);
        $job = trim($data['job']);
        $consecutivo = trim($data['consecutivo']);

        // Defaults para 4x2 @203dpi
        $pw = $data['pw'] ?? 812;
        $ll = $data['ll'] ?? 406;

        // Defaults de posiciones (ajústalas a tu gusto)
        $fgX = $data['fg_x'] ?? 330;     // número grande arriba
        $fgY = $data['fg_y'] ?? 40;

        $jobX = $data['job_x'] ?? 260;   // línea gris “QB...”
        $jobY = $data['job_y'] ?? 150;

        $consX = $data['cons_x'] ?? 350; // consecutivo abajo derecha
        $consY = $data['cons_y'] ?? 290;

        $qrX = $data['qr_x'] ?? 30;      // QR a la izquierda
        $qrY = $data['qr_y'] ?? 90;

        $qrM = $data['qr_m'] ?? 4;
        $qrModel = $data['qr_model'] ?? 2;
        $qrEcc = $data['qr_ecc'] ?? 'M'; // si luego quieres usarlo, se puede mapear

        // IMPORTANTE: si tus datos pueden traer ^ o \ o caracteres raros, sanitiza.
        // Para este caso, mínimo evitamos ^ para no romper ZPL:
        $fgSafe = str_replace('^', '', $fg);
        $jobSafe = str_replace('^', '', $job);
        $consSafe = str_replace('^', '', $consecutivo);

        // Payload EXACTO como tu ejemplo (con ^ al inicio/fin):
        $qrPayload = "^DM^{$fgSafe}^{$jobSafe}^{$consSafe}^";

        // ZPL
        $zpl = "^XA\n";
        $zpl .= "^PW{$pw}\n";
        $zpl .= "^LL{$ll}\n";
        $zpl .= "^CI28\n"; // UTF-8 (por si hay letras raras)

        // Título opcional
        $zpl .= "^FO30,20^A0N,30,30^FDRMT Dummy QR^FS\n";

        // FG (grande)
        $zpl .= "^FO{$fgX},{$fgY}^A0N,60,60^FD{$fgSafe}^FS\n";

        // JOB producción (más pequeño / gris en la foto; aquí solo tamaño menor)
        $zpl .= "^FO{$jobX},{$jobY}^A0N,28,28^FD{$jobSafe}^FS\n";

        // Consecutivo (abajo grande)
        $zpl .= "^FO{$consX},{$consY}^A0N,50,50^FD{$consSafe}^FS\n";

        // QR
        // Nota: ^BQN = QR Model 1/2, magnification M
        $zpl .= "^FO{$qrX},{$qrY}^BQN,{$qrModel},{$qrM}^FDLA,{$qrPayload}^FS\n";

        $zpl .= "^XZ";

        return response($zpl, 200)->header('Content-Type', 'text/plain');
    }
}
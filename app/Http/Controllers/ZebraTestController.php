<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ZebraTestController extends Controller
{
    public function index()
    {
        return view('testzebra');
    }

   public function zpl(Request $request)
{
    $data = $request->validate([
        'serial' => 'required|string|max:100',

        'pw' => 'nullable|integer|min:100|max:2000',
        'll' => 'nullable|integer|min:100|max:2000',

        'text_orientation' => 'nullable|string|in:N,R,B,I',

        'text_x' => 'nullable|integer|min:0|max:2000',
        'text_y' => 'nullable|integer|min:0|max:2000',
        'text_h' => 'nullable|integer|min:10|max:400',
        'text_w' => 'nullable|integer|min:10|max:400',

        'qr_x' => 'nullable|integer|min:0|max:2000',
        'qr_y' => 'nullable|integer|min:0|max:2000',
        'qr_m' => 'nullable|integer|min:1|max:10',
        'qr_model' => 'nullable|integer|in:1,2',
        'qr_ecc' => 'nullable|string|in:H,Q,M,L',
    ]);

    $serial = preg_replace('/[^A-Za-z0-9\-\_\.]/', '', $data['serial']);

    $pw = $data['pw'] ?? 812;
    $ll = $data['ll'] ?? 406;

    $textX = $data['text_x'] ?? 40;
    $textY = $data['text_y'] ?? 120;
    $textH = $data['text_h'] ?? 40;
    $textW = $data['text_w'] ?? 40;

    $qrX = $data['qr_x'] ?? 500;
    $qrY = $data['qr_y'] ?? 60;
    $qrM = $data['qr_m'] ?? 3;
    $qrModel = $data['qr_model'] ?? 2;
    $qrEcc = $data['qr_ecc'] ?? 'M';

    $textOrientation = $data['text_orientation'] ?? 'N';

    $zpl  = "^XA\n";
    $zpl .= "^PW{$pw}\n";
    $zpl .= "^LL{$ll}\n";
    $zpl .= "^LH0,0\n";
    $zpl .= "^FWN\n";

    // ✅ Texto del serial con orientación dinámica
    $zpl .= "^FO{$textX},{$textY}^A0{$textOrientation},{$textH},{$textW}^FD{$serial}^FS\n";

    // QR
    $zpl .= "^FO{$qrX},{$qrY}^BQN,{$qrModel},{$qrM},{$qrEcc}\n";
    $zpl .= "^FDLA,{$serial}^FS\n";

    $zpl .= "^XZ";

    return response($zpl)->header('Content-Type', 'text/plain');
}



}

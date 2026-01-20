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
    $request->validate([
        'serial' => 'required|string|max:100',
    ]);

    $serial = $request->serial;

    // Configuración de etiqueta (4x2 pulgadas, 203 dpi)
    $dpi = 203;
    $pw = 4 * $dpi;   // 812
    $ll = 2 * $dpi;   // 406

    $zpl  = "^XA\n";
    $zpl .= "^PW{$pw}\n";
    $zpl .= "^LL{$ll}\n";
    $zpl .= "^LH0,0\n";
    $zpl .= "^FWN\n";

    // Texto del serial
    $zpl .= "^FO40,120^A0N,40,40^FD{$serial}^FS\n";

    // Código QR con el serial
    $zpl .= "^FO500,60^BQN,2,3\n";
    $zpl .= "^FDLA,{$serial}^FS\n";

    $zpl .= "^XZ";

    return response($zpl)->header('Content-Type', 'text/plain');
}

}

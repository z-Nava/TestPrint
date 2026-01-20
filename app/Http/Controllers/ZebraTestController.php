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

    // Configuración de etiqueta (ajusta si no es 4x2)
    $dpi = 203;
    $wIn = 4;
    $hIn = 2;

    $pw = $wIn * $dpi;   // 812
    $ll = $hIn * $dpi;   // 406

    // Offset global (mueve todo)
    $lhX = 400;
    $lhY = 50;

    $zpl  = "^XA\n";
        $zpl .= "^PW{$pw}\n";
        $zpl .= "^LL{$ll}\n";
        $zpl .= "^LH{$lhX},{$lhY}\n";
        $zpl .= "^FWN\n";

        // Serial grande y claro
        $zpl .= "^FO50,20^A0N,20,20^FD{$serial}^FS\n";

        $zpl .= "^XZ";

        return response($zpl)->header('Content-Type', 'text/plain');
    }
}

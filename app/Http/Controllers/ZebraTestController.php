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

        $zpl = "^XA\n";
        $zpl .= "^PW812\n";   // ajusta según tu etiqueta
        $zpl .= "^LL406\n";
        $zpl .= "^CF0,40\n";
        $zpl .= "^FO50,50^FDPRUEBA ZEBRA^FS\n";
        $zpl .= "^FO50,120^FDSerial: {$serial}^FS\n";
        $zpl .= "^FO50,190^BCN,80,Y,N,N\n";
        $zpl .= "^FD{$serial}^FS\n";
        $zpl .= "^XZ";

        return response($zpl)->header('Content-Type', 'text/plain');
    }
}

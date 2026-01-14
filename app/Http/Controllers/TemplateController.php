<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    public function open(string $name)
    {
        $map = [
            '479162001-ds' => '479162001 - DS.btw',
            // agrega más aquí:
            // 'rmt-dummy-qr' => 'RMT Dummy QR.btw',
        ];

        if (!isset($map[$name])) {
            abort(404);
        }

        $path = "templates/" . $map[$name];

        if (!Storage::exists($path)) {
            abort(404);
        }

        return Storage::download($path, $map[$name]);
    }

}

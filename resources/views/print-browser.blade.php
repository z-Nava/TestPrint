<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Impresión</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @media print {
            body { margin: 0; }
            .page-break { page-break-after: always; }
        }
    </style>
</head>
<body onload="window.print()">

@for ($i = 1; $i <= $qty; $i++)
    <div class="w-[300px] h-[150px] border border-black p-2 m-2">
        <p class="text-sm"><strong>Job:</strong> {{ $job }}</p>
        <p class="text-sm"><strong>FG:</strong> {{ $fg }}</p>
        <p class="text-sm"><strong>Línea:</strong> {{ $line }}</p>
        <p class="text-sm"><strong>#:</strong> {{ $i }}</p>

        <div class="mt-2 text-xs break-all">
            QR: JOB={{ $job }}|FG={{ $fg }}|LINE={{ $line }}|SEQ={{ $i }}
        </div>
    </div>

    @if($i < $qty)
        <div class="page-break"></div>
    @endif
@endfor

</body>
</html>

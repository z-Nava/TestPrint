<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Job Validation</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100">
<div class="min-h-screen p-6">
    <div class="max-w-6xl mx-auto space-y-4">

        <div class="bg-white rounded-2xl shadow p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold">Job Validation (Excel)</h1>
                    <p class="text-slate-600 mt-1">
                        Fuente: <span class="font-mono text-xs">{{ $source }}</span>
                    </p>
                    @if($cached_at)
                        <p class="text-xs text-slate-500 mt-1">Última lectura: {{ $cached_at }}</p>
                    @endif
                </div>

                <form method="POST" action="{{ route('job.validation.reload') }}">
                    @csrf
                    <button class="rounded-xl bg-slate-900 text-white px-4 py-2 font-semibold hover:bg-slate-800">
                        Recargar
                    </button>
                </form>
            </div>

            @if(session('ok'))
                <div class="mt-4 rounded-xl bg-green-50 text-green-700 px-4 py-3">
                    {{ session('ok') }}
                </div>
            @endif
        </div>
        <form method="GET" action="{{ route('job.validation') }}" class="flex gap-2">
                    <input name="job" value="{{ $jobFilter ?? '' }}"
                        placeholder="Buscar JOB_NO..."
                        class="rounded-xl border border-slate-300 px-3 py-2">
                    <button class="rounded-xl bg-blue-600 text-white px-4 py-2 font-semibold hover:bg-blue-500">
                        Buscar
                    </button>
        </form>
        
        <div class="bg-white rounded-2xl shadow p-6 overflow-auto">
            @if(empty($columns))
                <p class="text-red-600">No se pudieron detectar columnas. Revisa encabezados o la ruta del archivo.</p>
            @else
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left border-b">
                            @foreach($columns as $c)
                                <th class="py-2 px-3 font-semibold text-slate-700">{{ $c }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $r)
                            <tr class="border-b hover:bg-slate-50">
                                @foreach($columns as $c)
                                    <td class="py-2 px-3 text-slate-700 whitespace-nowrap">
                                        {{ $r[$c] ?? '' }}
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td class="py-4 px-3 text-slate-500" colspan="{{ count($columns) }}">
                                    Sin filas para mostrar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>

    </div>
</div>
</body>
</html>

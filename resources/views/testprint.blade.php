<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Demo Impresión</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100">
<div class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-3xl space-y-6">

        <!-- Header -->
        <div class="bg-white rounded-2xl shadow p-6">
            <h1 class="text-2xl font-semibold">Demo: Impresión y Templates</h1>
            <p class="text-slate-600 mt-1">
                Pruebas: abrir template (.btw), imprimir desde navegador y generar CSV para BarTender.
            </p>
        </div>

        <!-- A) Abrir Template -->
        <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-lg font-semibold">A) Abrir Template (.btw)</h2>
            <p class="text-sm text-slate-600 mt-1">
                Descarga/abre el archivo .btw. Si Windows lo tiene asociado a BarTender, se abrirá solo.
            </p>

            <div class="mt-4 space-y-3">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Template</label>
                    <select id="tpl"
                            class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 focus:border-slate-400 focus:outline-none">
                        <option value="479162001-ds">479162001 - DS</option>
                    </select>
                </div>

                <button type="button" onclick="openTemplate()"
                        class="w-full rounded-xl bg-blue-600 text-white py-3 font-semibold hover:bg-blue-500 transition">
                    Abrir template (.btw)
                </button>

                <p class="text-xs text-slate-500">
                    Nota: el navegador puede descargar el archivo primero; eso es normal.
                </p>
            </div>
        </div>

        <!-- B) Imprimir desde navegador -->
        <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-lg font-semibold">B) Imprimir desde navegador</h2>
            <p class="text-sm text-slate-600 mt-1">
                Abre una pestaña nueva y dispara <code>window.print()</code>.
            </p>

            <form class="mt-4 space-y-4" method="POST" action="{{ route('print.browser') }}" target="_blank">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700">Job</label>
                    <input name="job" value="{{ old('job', 'JOB12345') }}"
                           class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 focus:border-slate-400 focus:outline-none">
                    @error('job') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">FG</label>
                    <input name="fg" value="{{ old('fg', 'FG-0001') }}"
                           class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 focus:border-slate-400 focus:outline-none">
                    @error('fg') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Línea</label>
                    <input name="line" value="{{ old('line', 'MXC032') }}"
                           class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 focus:border-slate-400 focus:outline-none">
                    @error('line') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Cantidad de etiquetas</label>
                    <input type="number" name="qty" value="{{ old('qty', 5) }}"
                           min="1" max="1000"
                           class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 focus:border-slate-400 focus:outline-none">
                    @error('qty') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit"
                        class="w-full rounded-xl bg-slate-900 text-white py-3 font-semibold hover:bg-slate-800 transition">
                    Imprimir (navegador)
                </button>
            </form>
        </div>

        <!-- C) Descargar CSV para BarTender -->
        <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-lg font-semibold">C) Descargar CSV (BarTender)</h2>
            <p class="text-sm text-slate-600 mt-1">
                Genera un CSV para usarlo como Data Source en BarTender.
            </p>

            <form class="mt-4 space-y-4" method="POST" action="{{ route('print.csv') }}">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700">Job</label>
                    <input name="job" value="{{ old('job', 'JOB12345') }}"
                           class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 focus:border-slate-400 focus:outline-none">
                    @error('job') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">FG</label>
                    <input name="fg" value="{{ old('fg', 'FG-0001') }}"
                           class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 focus:border-slate-400 focus:outline-none">
                    @error('fg') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Línea</label>
                    <input name="line" value="{{ old('line', 'MXC032') }}"
                           class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 focus:border-slate-400 focus:outline-none">
                    @error('line') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Cantidad de etiquetas</label>
                    <input type="number" name="qty" value="{{ old('qty', 5) }}"
                           min="1" max="1000"
                           class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 focus:border-slate-400 focus:outline-none">
                    @error('qty') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit"
                        class="w-full rounded-xl bg-green-600 text-white py-3 font-semibold hover:bg-green-500 transition">
                    Descargar CSV
                </button>

                <p class="text-xs text-slate-500">
                    En BarTender: abre el template y selecciona este CSV como Data Source.
                </p>
            </form>
        </div>

    </div>
</div>

<script>
function openTemplate() {
    const tpl = document.getElementById('tpl').value;
    window.open(`/templates/${encodeURIComponent(tpl)}`, '_blank');
}
</script>
</body>
</html>

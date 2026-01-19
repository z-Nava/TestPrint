<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Master Ensamble</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100">
  <div class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-xl bg-white rounded-2xl shadow p-6">
      <h1 class="text-2xl font-semibold">Generar Master Ensamble (prueba)</h1>
      <p class="text-slate-600 mt-1">
        Por ahora solo escribe la <strong>JOB</strong> en el master XLSX y genera PDF.
      </p>

      @if(session('error'))
        <div class="mt-4 rounded-xl bg-red-50 text-red-700 px-4 py-3">
          {{ session('error') }}
        </div>
      @endif

      <form class="mt-6 space-y-4" method="POST" action="{{ route('master.ensamble.pdf') }}" target="_blank">
        @csrf

        <div>
          <label class="block text-sm font-medium text-slate-700">JOB_NUMBER</label>
          <input name="job_number" value="{{ old('job_number','385434') }}"
                 class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 focus:border-slate-400 focus:outline-none"
                 placeholder="Ej. 385434">
          @error('job_number')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
          @enderror
        </div>

        <button type="submit"
                class="w-full rounded-xl bg-slate-900 text-white py-3 font-semibold hover:bg-slate-800 transition">
          Generar PDF
        </button>

        <p class="text-xs text-slate-500">
          Asegúrate de tener la plantilla en: <code>storage/app/templates/master_ensamble.xlsx</code>
        </p>
      </form>

      <a href="/" class="mt-4 inline-block text-sm text-blue-700 hover:underline">← Volver al menú</a>
    </div>
  </div>
</body>
</html>

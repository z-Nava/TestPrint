<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>404 | Página no encontrada</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-6">
  <div class="w-full max-w-xl bg-white rounded-2xl shadow p-8 text-center space-y-4">
    <div class="text-6xl font-black text-slate-900">404</div>
    <h1 class="text-2xl font-semibold text-slate-800">Página no encontrada</h1>
    <p class="text-slate-600">
      La ruta que intentaste abrir no existe o fue movida.
    </p>
    <p class="text-sm text-slate-500">
      Si crees que esto es un error, por favor contacta al administrador del sitio.
    </p>

    <div class="flex gap-3 justify-center pt-2">
      <a href="{{ url('/') }}"
         class="px-5 py-3 rounded-xl bg-orange-600 text-white font-semibold hover:bg-orange-500">
        Ir al inicio
      </a>
      <a href="{{ url()->previous() }}"
         class="px-5 py-3 rounded-xl border border-slate-300 text-slate-700 font-semibold hover:bg-slate-50">
        Volver
      </a>
    </div>

    <p class="text-xs text-slate-500 pt-4">
      {{ now()->format('Y-m-d H:i') }}
    </p>
  </div>
</body>
</html>

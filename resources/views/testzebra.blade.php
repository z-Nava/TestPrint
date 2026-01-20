<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Prueba Zebra</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Zebra Browser Print -->
    <script src="{{ asset('vendor/zebra/BrowserPrint-3.1.250.min.js') }}"></script>
</head>
<body class="bg-slate-100">
<div class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-xl bg-white rounded-2xl shadow p-6 space-y-4">

        <h1 class="text-2xl font-semibold">Prueba Zebra - Browser Print</h1>

        <div id="status" class="text-sm text-slate-600">
            Detectando impresora...
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Serial</label>
            <input id="serial"
                   class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2">
        </div>

        <button onclick="imprimir()"
                class="w-full rounded-xl bg-orange-600 text-white py-3 font-semibold hover:bg-orange-500 transition">
            Imprimir Zebra
        </button>

        <a href="{{ url()->previous() }}"
           class="block text-center text-slate-500 hover:underline">
            ← Volver
        </a>
    </div>
</div>

<script>
BrowserPrint.useHttps = true;
let zebra = null;

function setStatus(html) {
  document.getElementById("status").innerHTML = html;
}

// 👇 ESTA FUNCIÓN DEBE ESTAR AQUÍ, GLOBAL
function imprimir() {
  const serial = document.getElementById("serial").value.trim();

  if (!serial) {
    alert("Ingresa un serial");
    return;
  }
  if (!zebra) {
    alert("No hay impresora detectada");
    return;
  }

  fetch("{{ route('zebra.zpl') }}", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": "{{ csrf_token() }}"
    },
    body: JSON.stringify({ serial })
  })
  .then(r => r.text())
  .then(zpl => {
    zebra.send(zpl,
      () => alert("Enviado a imprimir"),
      e => alert("Error al imprimir: " + e)
    );
  })
  .catch(e => alert("Error fetch: " + e));
}

// Solo lógica de detección aquí
window.addEventListener("load", () => {
  if (typeof BrowserPrint === "undefined") {
    setStatus("No se cargó BrowserPrint");
    return;
  }

  BrowserPrint.getLocalDevices(function(devicesRaw) {
    const devices = Array.isArray(devicesRaw) ? devicesRaw : [devicesRaw];
    const printers = devices.filter(d => d && d.deviceType === "printer");

    if (!printers.length) {
      zebra = null;
      setStatus("No se detectaron impresoras");
      return;
    }

    zebra = printers[0];
    setStatus("Impresora detectada: " + zebra.name);
  }, function(err) {
    zebra = null;
    setStatus("Error: " + JSON.stringify(err));
  }, "printer");
});
</script>
</body>
</html>

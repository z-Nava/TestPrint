<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Prueba Zebra</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Zebra Browser Print -->
    <script src="http://localhost:9100/BrowserPrint-2.0.1.min.js"></script>
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
let zebra = null;

BrowserPrint.getDefaultDevice("printer", function(device) {
    zebra = device;
    document.getElementById("status").innerHTML =
        "Impresora detectada: <b>" + device.name + "</b>";
}, function() {
    document.getElementById("status").innerHTML =
        "<span class='text-red-600'>No se detectó Zebra Browser Print</span>";
});

function imprimir() {
    const serial = document.getElementById("serial").value;
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
    });
}
</script>
</body>
</html>

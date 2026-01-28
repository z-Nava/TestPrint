<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Prueba Zebra</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="{{ asset('vendor/zebra/BrowserPrint-3.1.250.min.js') }}"></script>
</head>
<body class="bg-slate-100">
<div class="min-h-screen flex items-center justify-center p-6">
  <div class="w-full max-w-3xl bg-white rounded-2xl shadow p-6 space-y-5">

    <div class="flex items-start justify-between gap-4">
      <div>
        <h1 class="text-2xl font-semibold">Prueba Zebra - Browser Print</h1>
        <div id="status" class="text-sm text-slate-600 mt-1">Detectando impresora...</div>
      </div>
      <div class="text-xs text-slate-500 text-right">
        Tip: Ajusta X/Y y usa “Preview ZPL”
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <!-- Datos -->
      <div class="rounded-2xl border border-slate-200 p-4 space-y-3">
        <h2 class="font-semibold text-slate-800">Datos</h2>

        <div>
          <label class="block text-sm font-medium text-slate-700">Serial</label>
          <input id="serial" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2" placeholder="Ej: S74-25-0154">
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-sm font-medium text-slate-700">PW (dots)</label>
            <input id="pw" type="number" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2" value="812">
            <p class="text-xs text-slate-500 mt-1">4in @ 203dpi = 812</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700">LL (dots)</label>
            <input id="ll" type="number" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2" value="406">
            <p class="text-xs text-slate-500 mt-1">2in @ 203dpi = 406</p>
          </div>
        </div>
      </div>

      <!-- Config -->
      <div class="rounded-2xl border border-slate-200 p-4 space-y-3">
        <h2 class="font-semibold text-slate-800">Configuración (coordenadas)</h2>
        <div>
          <label class="block text-sm font-medium text-slate-700">Orientación texto</label>
          <select id="text_orientation"
                  class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2">
            <option value="N" selected>Horizontal</option>
            <option value="R">Vertical (90°)</option>
            <option value="B">Vertical (270°)</option>
            <option value="I">Invertido (180°)</option>
          </select>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-sm font-medium text-slate-700">Texto X</label>
            <input id="text_x" type="number" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2" value="40">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700">Texto Y</label>
            <input id="text_y" type="number" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2" value="120">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-sm font-medium text-slate-700">Texto alto (h)</label>
            <input id="text_h" type="number" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2" value="40">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700">Texto ancho (w)</label>
            <input id="text_w" type="number" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2" value="40">
          </div>
        </div>

        <hr class="my-2">

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-sm font-medium text-slate-700">QR X</label>
            <input id="qr_x" type="number" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2" value="500">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700">QR Y</label>
            <input id="qr_y" type="number" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2" value="60">
          </div>
        </div>

        <div class="grid grid-cols-3 gap-3">
          <div>
            <label class="block text-sm font-medium text-slate-700">QR M</label>
            <input id="qr_m" type="number" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2" value="3" min="1" max="10">
            <p class="text-xs text-slate-500 mt-1">Tamaño (1-10)</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700">QR Modelo</label>
            <select id="qr_model" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2">
              <option value="2" selected>2 (recomendado)</option>
              <option value="1">1</option>
            </select>
            <p class="text-xs text-slate-500 mt-1">^BQN,model</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700">ECC</label>
            <select id="qr_ecc" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2">
              <option value="H">H</option>
              <option value="Q">Q</option>
              <option value="M" selected>M</option>
              <option value="L">L</option>
            </select>
            <p class="text-xs text-slate-500 mt-1">Tolerancia error</p>
          </div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <button onclick="previewZPL()"
              class="w-full rounded-xl bg-slate-900 text-white py-3 font-semibold hover:bg-slate-800 transition">
        Preview ZPL
      </button>

      <button onclick="imprimir()"
              class="w-full rounded-xl bg-orange-600 text-white py-3 font-semibold hover:bg-orange-500 transition">
        Imprimir Zebra
      </button>
    </div>

    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
      <div class="flex items-center justify-between">
        <h3 class="font-semibold text-slate-800">ZPL generado</h3>
        <button onclick="copiarZPL()"
                class="text-sm px-3 py-1 rounded-lg border border-slate-300 hover:bg-white">
          Copiar
        </button>
      </div>
      <pre id="zpl_preview" class="mt-3 text-xs overflow-auto whitespace-pre-wrap break-words text-slate-700"></pre>
    </div>

    <a href="{{ url()->previous() }}" class="block text-center text-slate-500 hover:underline">
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

function getPayload() {
  const payload = {
    serial: document.getElementById("serial").value.trim(),

    pw: parseInt(document.getElementById("pw").value || "812", 10),
    ll: parseInt(document.getElementById("ll").value || "406", 10),

    text_orientation: document.getElementById("text_orientation").value || "N",

    text_x: parseInt(document.getElementById("text_x").value || "40", 10),
    text_y: parseInt(document.getElementById("text_y").value || "120", 10),
    text_h: parseInt(document.getElementById("text_h").value || "40", 10),
    text_w: parseInt(document.getElementById("text_w").value || "40", 10),

    qr_x: parseInt(document.getElementById("qr_x").value || "500", 10),
    qr_y: parseInt(document.getElementById("qr_y").value || "60", 10),
    qr_m: parseInt(document.getElementById("qr_m").value || "3", 10),
    qr_model: parseInt(document.getElementById("qr_model").value || "2", 10),
    qr_ecc: document.getElementById("qr_ecc").value || "M",
  };

  return payload;
}

function setPreview(zpl) {
  document.getElementById("zpl_preview").textContent = zpl || "";
}

function copiarZPL() {
  const text = document.getElementById("zpl_preview").textContent || "";
  if (!text.trim()) return;
  navigator.clipboard.writeText(text).then(() => setStatus("✅ ZPL copiado."));
}

function previewZPL() {
  const payload = getPayload();
  if (!payload.serial) {
    setStatus("❌ Ingresa un serial.");
    return;
  }

  setStatus("Generando preview...");
  fetch("{{ route('zebra.zpl') }}", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": "{{ csrf_token() }}",
      "Accept": "text/plain"
    },
    body: JSON.stringify(payload)
  })
  .then(async (r) => {
    const txt = await r.text();
    if (!r.ok) throw new Error(`HTTP ${r.status}: ${txt}`);
    return txt;
  })
  .then(zpl => {
    setPreview(zpl);
    setStatus("✅ Preview listo. Ajusta X/Y y vuelve a generar.");
  })
  .catch(e => setStatus("❌ Error preview: " + e.message));
}

function imprimir() {
  const payload = getPayload();

  if (!payload.serial) { setStatus("❌ Ingresa un serial."); return; }
  if (!zebra) { setStatus("❌ No hay impresora detectada."); return; }

  setStatus("Generando ZPL para impresión...");

  fetch("{{ route('zebra.zpl') }}", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": "{{ csrf_token() }}",
      "Accept": "text/plain"
    },
    body: JSON.stringify(payload)
  })
  .then(async (r) => {
    const txt = await r.text();
    if (!r.ok) throw new Error(`HTTP ${r.status}: ${txt}`);
    return txt;
  })
  .then(zpl => {
    setPreview(zpl);
    setStatus("Enviando a impresora...");
    zebra.send(zpl,
      () => setStatus("✅ Enviado a imprimir."),
      (e) => setStatus("❌ Error al imprimir: " + (typeof e === "string" ? e : JSON.stringify(e)))
    );
  })
  .catch(e => setStatus("❌ Error: " + e.message));
}

window.addEventListener("load", () => {
  if (typeof BrowserPrint === "undefined") {
    setStatus("❌ No se cargó BrowserPrint.");
    return;
  }

  setStatus("Buscando impresora por defecto...");

  BrowserPrint.getDefaultDevice("printer",
    (device) => { zebra = device; setStatus("✅ Impresora: " + zebra.name); },
    () => {
      BrowserPrint.getLocalDevices(
        (devicesRaw) => {
          const devices = Array.isArray(devicesRaw) ? devicesRaw : [devicesRaw];
          const printers = devices.filter(d => ((d?.deviceType || "").toString().toLowerCase()).includes("printer"));
          if (!printers.length) { zebra = null; setStatus("❌ No se detectaron impresoras."); return; }
          zebra = printers[0];
          setStatus("✅ Impresora detectada: " + zebra.name);
        },
        (err2) => { zebra = null; setStatus("❌ Error: " + JSON.stringify(err2)); },
        "printer"
      );
    }
  );
});
</script>
</body>
</html>

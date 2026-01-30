<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Dummy QR Test</title>
  @vite(['resources/js/app.js'])
  <script src="{{ asset('vendor/zebra/BrowserPrint-3.1.250.min.js') }}"></script>
</head>
<body class="bg-slate-100">
<div class="min-h-screen flex items-center justify-center p-6">
  <div class="w-full max-w-4xl bg-white rounded-2xl shadow p-6 space-y-5">

    <div class="flex items-start justify-between gap-4">
      <div>
        <h1 class="text-2xl font-semibold">Dummy QR - Zebra BrowserPrint</h1>
        <div id="status" class="text-sm text-slate-600 mt-1">Detectando impresora...</div>
      </div>
      <div class="text-xs text-slate-500 text-right">
        Tip: Ajusta X/Y si se mueve<br>Primero “Preview ZPL”
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <!-- Datos -->
      <div class="rounded-2xl border border-slate-200 p-4 space-y-3">
        <h2 class="font-semibold text-slate-800">Datos (manipulables)</h2>

        <div>
          <label class="block text-sm font-medium text-slate-700">FG</label>
          <input id="fg" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2" placeholder="Ej: 479124001">
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700">JOB Producción</label>
          <input id="job" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2" placeholder="Ej: QB479124001UN-A01-OP21PSE">
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700">Consecutivo</label>
          <input id="consecutivo" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2" placeholder="Ej: 0000000014">
        </div>

        <div class="grid grid-cols-2 gap-3 pt-2">
          <div>
            <label class="block text-sm font-medium text-slate-700">PW</label>
            <input id="pw" type="number" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2" value="812">
            <p class="text-xs text-slate-500 mt-1">4in @ 203dpi</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700">LL</label>
            <input id="ll" type="number" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2" value="406">
            <p class="text-xs text-slate-500 mt-1">2in @ 203dpi</p>
          </div>
        </div>
      </div>

      <!-- Coordenadas -->
      <div class="rounded-2xl border border-slate-200 p-4 space-y-3">
        <h2 class="font-semibold text-slate-800">Coordenadas</h2>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-sm font-medium text-slate-700">FG X</label>
            <input id="fg_x" type="number" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2" value="330">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700">FG Y</label>
            <input id="fg_y" type="number" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2" value="40">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-sm font-medium text-slate-700">JOB X</label>
            <input id="job_x" type="number" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2" value="260">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700">JOB Y</label>
            <input id="job_y" type="number" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2" value="150">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-sm font-medium text-slate-700">Consec X</label>
            <input id="cons_x" type="number" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2" value="350">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700">Consec Y</label>
            <input id="cons_y" type="number" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2" value="290">
          </div>
        </div>

        <hr class="my-2">

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-sm font-medium text-slate-700">QR X</label>
            <input id="qr_x" type="number" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2" value="30">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700">QR Y</label>
            <input id="qr_y" type="number" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2" value="90">
          </div>
        </div>

        <div class="grid grid-cols-3 gap-3">
          <div>
            <label class="block text-sm font-medium text-slate-700">QR M</label>
            <input id="qr_m" type="number" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2" value="4" min="1" max="10">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700">Modelo</label>
            <select id="qr_model" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2">
              <option value="2" selected>2</option>
              <option value="1">1</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700">ECC</label>
            <select id="qr_ecc" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2">
              <option value="H">H</option>
              <option value="Q">Q</option>
              <option value="M" selected>M</option>
              <option value="L">L</option>
            </select>
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

  </div>
</div>

<script>
  // Recomendación: no forzar HTTPS siempre
  BrowserPrint.useHttps = (location.protocol === "https:");

  let zebra = null;

  function setStatus(html) { document.getElementById("status").innerHTML = html; }
  function setPreview(zpl) { document.getElementById("zpl_preview").textContent = zpl || ""; }

  function getPayload() {
    return {
      fg: document.getElementById("fg").value.trim(),
      job: document.getElementById("job").value.trim(),
      consecutivo: document.getElementById("consecutivo").value.trim(),

      pw: parseInt(document.getElementById("pw").value || "812", 10),
      ll: parseInt(document.getElementById("ll").value || "406", 10),

      fg_x: parseInt(document.getElementById("fg_x").value || "330", 10),
      fg_y: parseInt(document.getElementById("fg_y").value || "40", 10),

      job_x: parseInt(document.getElementById("job_x").value || "260", 10),
      job_y: parseInt(document.getElementById("job_y").value || "150", 10),

      cons_x: parseInt(document.getElementById("cons_x").value || "350", 10),
      cons_y: parseInt(document.getElementById("cons_y").value || "290", 10),

      qr_x: parseInt(document.getElementById("qr_x").value || "30", 10),
      qr_y: parseInt(document.getElementById("qr_y").value || "90", 10),
      qr_m: parseInt(document.getElementById("qr_m").value || "4", 10),
      qr_model: parseInt(document.getElementById("qr_model").value || "2", 10),
      qr_ecc: document.getElementById("qr_ecc").value || "M",
    };
  }

  function copiarZPL() {
    const text = document.getElementById("zpl_preview").textContent || "";
    if (!text.trim()) return;
    navigator.clipboard.writeText(text)
      .then(() => setStatus("✅ ZPL copiado."))
      .catch(() => setStatus("❌ No se pudo copiar (requiere HTTPS/permisos)."));
  }

  function valida(payload) {
    if (!payload.fg) return "Ingresa FG.";
    if (!payload.job) return "Ingresa JOB Producción.";
    if (!payload.consecutivo) return "Ingresa Consecutivo.";
    return null;
  }

  function previewZPL() {
    const payload = getPayload();
    const err = valida(payload);
    if (err) { setStatus("❌ " + err); return; }

    setStatus("Generando preview...");
    fetch("{{ route('dummyqr.zpl') }}", {
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
    const err = valida(payload);
    if (err) { setStatus("❌ " + err); return; }
    if (!zebra) { setStatus("❌ No hay impresora detectada."); return; }

    setStatus("Generando ZPL para impresión...");
    fetch("{{ route('dummyqr.zpl') }}", {
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
      setStatus("❌ No se cargó BrowserPrint (revisa que el JS exista y el servicio esté instalado).");
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
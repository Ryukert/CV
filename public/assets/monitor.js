// Monitor SHM simulado del encabezado: genera la señal de un acelerómetro a 200 Hz (tres modos
// de vibración de una estructura + ruido + un «evento» de vez en cuando que decae), calcula su
// espectro con una FFT de 512 puntos (ventana de Hann) y dibuja las dos cosas en <canvas>.
// También muestra la frecuencia dominante y el RMS, como el sistema SHM real.
//
// Mejora progresiva: sin JavaScript el monitor no aparece (atributo hidden). Con «reducir
// movimiento» se dibuja un cuadro fijo y queda en pausa. Se detiene cuando no se ve.
(function () {
  'use strict';

  var fig = document.getElementById('monitor');
  var ondaCv = document.getElementById('monitor-onda');
  var fftCv = document.getElementById('monitor-fft');
  if (!fig || !ondaCv || !fftCv || !ondaCv.getContext) return;
  var cO = ondaCv.getContext('2d');
  var cF = fftCv.getContext('2d');
  if (!cO || !cF) return;

  var T = {};
  try { T = JSON.parse(document.getElementById('cv-textos').textContent).monitor || {}; } catch (e) { T = {}; }
  var f0El = document.getElementById('monitor-f0');
  var rmsEl = document.getElementById('monitor-rms');
  var estadoEl = document.getElementById('monitor-estado');
  var boton = document.getElementById('monitor-pausa');

  // ── Señal sintética ──
  var FS = 200, N = 512, DOS_PI = 2 * Math.PI;
  var modos = [
    { f: 2.44, a: 0.50, p: 0.0 },   // primer modo de la estructura
    { f: 7.81, a: 0.20, p: 1.3 },
    { f: 13.2, a: 0.10, p: 2.1 },
  ];
  var buf = new Float32Array(N);
  var n = 0;                          // número de muestra
  var evento = 0;                     // amplitud del evento en curso
  var proximo = FS * (5 + Math.random() * 5);

  function muestra() {
    var tt = n / FS, s = 0;
    for (var i = 0; i < modos.length; i++) {
      var m = modos[i];
      s += m.a * Math.sin(DOS_PI * m.f * tt + m.p) * (1 + 0.2 * Math.sin(DOS_PI * 0.05 * tt + m.p));
    }
    s += (Math.random() - 0.5) * 0.16;
    if (n >= proximo) { evento = 1.8; proximo = n + FS * (9 + Math.random() * 9); }
    if (evento > 0.005) {
      s += evento * Math.sin(DOS_PI * 4.6 * tt) * (0.7 + 0.3 * Math.sin(DOS_PI * 11 * tt));
      evento *= 0.9925;               // decae a ~22 % en un segundo
    }
    n++;
    return s * 0.45;
  }
  for (var k = 0; k < N; k++) buf[k] = muestra();

  // ── FFT radix-2 (Cooley-Tukey) con ventana de Hann ──
  var hann = new Float64Array(N);
  for (k = 0; k < N; k++) hann[k] = 0.5 * (1 - Math.cos(DOS_PI * k / (N - 1)));
  var bits = Math.log2(N);
  var inv = new Uint16Array(N);
  for (k = 0; k < N; k++) {
    var r = 0;
    for (var b = 0; b < bits; b++) r = (r << 1) | ((k >> b) & 1);
    inv[k] = r;
  }
  var re = new Float64Array(N), im = new Float64Array(N), mag = new Float64Array(N / 2);

  function espectro() {
    var media = 0;
    for (var i = 0; i < N; i++) media += buf[i];
    media /= N;
    for (i = 0; i < N; i++) { re[inv[i]] = (buf[i] - media) * hann[i]; im[inv[i]] = 0; }
    for (var tam = 2; tam <= N; tam <<= 1) {
      var mitad = tam >> 1, paso = -DOS_PI / tam;
      for (var ini = 0; ini < N; ini += tam) {
        for (var j = 0; j < mitad; j++) {
          var ang = paso * j, wr = Math.cos(ang), wi = Math.sin(ang);
          var a = ini + j, c = a + mitad;
          var tr = wr * re[c] - wi * im[c], ti = wr * im[c] + wi * re[c];
          re[c] = re[a] - tr; im[c] = im[a] - ti;
          re[a] += tr; im[a] += ti;
        }
      }
    }
    for (i = 0; i < N / 2; i++) mag[i] = Math.sqrt(re[i] * re[i] + im[i] * im[i]) / N;
  }

  // ── Dibujo ──
  var colores = {};
  function leerColores() {
    var cs = getComputedStyle(fig);
    colores.onda = cs.getPropertyValue('--monitor-onda').trim() || '#5cc27a';
    colores.barra = cs.getPropertyValue('--monitor-barra').trim() || '#d4a52a';
    colores.reticula = cs.getPropertyValue('--monitor-reticula').trim() || 'rgba(255,255,255,.08)';
    colores.texto = cs.getPropertyValue('--monitor-texto').trim() || 'rgba(255,255,255,.55)';
  }

  function ajustar(cv) {
    var rect = cv.getBoundingClientRect(), dpr = Math.min(2, window.devicePixelRatio || 1);
    var w = Math.max(1, Math.round(rect.width * dpr)), h = Math.max(1, Math.round(rect.height * dpr));
    if (cv.width !== w || cv.height !== h) { cv.width = w; cv.height = h; }
    return dpr;
  }

  function reticula(ctx, w, h, cols, filas) {
    ctx.strokeStyle = colores.reticula;
    ctx.lineWidth = 1;
    ctx.beginPath();
    for (var i = 1; i < cols; i++) { var x = Math.round(w * i / cols) + 0.5; ctx.moveTo(x, 0); ctx.lineTo(x, h); }
    for (i = 1; i < filas; i++) { var y = Math.round(h * i / filas) + 0.5; ctx.moveTo(0, y); ctx.lineTo(w, y); }
    ctx.stroke();
  }

  var FMAX = 30;                                   // Hz que se muestran en el espectro
  var KMAX = Math.round(FMAX * N / FS);            // último bin mostrado

  function dibujar() {
    var dpr = ajustar(ondaCv), w = ondaCv.width, h = ondaCv.height;
    cO.clearRect(0, 0, w, h);
    reticula(cO, w, h, 8, 4);
    cO.strokeStyle = colores.onda;
    cO.lineWidth = 1.5 * dpr;
    cO.lineJoin = 'round';
    cO.beginPath();
    var escala = h / 2 / 1.25;
    for (var i = 0; i < N; i++) {
      var x = i / (N - 1) * w, y = h / 2 - buf[i] * escala;
      if (i === 0) cO.moveTo(x, y); else cO.lineTo(x, y);
    }
    cO.stroke();

    espectro();
    dpr = ajustar(fftCv); w = fftCv.width; h = fftCv.height;
    cF.clearRect(0, 0, w, h);
    reticula(cF, w, h, 6, 3);
    var maximo = 1e-9, kPico = 1;
    for (i = 1; i <= KMAX; i++) if (mag[i] > maximo) { maximo = mag[i]; kPico = i; }
    var ancho = w / KMAX;
    cF.fillStyle = colores.barra;
    for (i = 1; i <= KMAX; i++) {
      var alto = Math.max(1, Math.sqrt(mag[i] / maximo) * (h - 12 * dpr));
      cF.fillRect((i - 1) * ancho + 0.5, h - alto, Math.max(1, ancho - 1), alto);
    }
    cF.fillStyle = colores.texto;
    cF.font = (9 * dpr) + 'px ui-monospace, SFMono-Regular, Consolas, monospace';
    cF.textBaseline = 'top';
    cF.fillText('0', 2 * dpr, 2 * dpr);
    cF.textAlign = 'right';
    cF.fillText(FMAX + ' Hz', w - 2 * dpr, 2 * dpr);
    cF.textAlign = 'left';

    // Frecuencia dominante con interpolación parabólica entre bins vecinos.
    var a = mag[kPico - 1] || 0, bb = mag[kPico], c = mag[kPico + 1] || 0;
    var den = a - 2 * bb + c, delta = den ? 0.5 * (a - c) / den : 0;
    var f0 = (kPico + delta) * FS / N;
    var suma = 0;
    for (i = 0; i < N; i++) suma += buf[i] * buf[i];
    return { f0: f0, rms: Math.sqrt(suma / N) };
  }

  // ── Bucle ──
  var reducido = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
  var pausado = reducido, visible = true, ultimo = 0, resto = 0, cuadro = 0, pedido = 0;

  function datos(r) {
    if (f0El) f0El.textContent = r.f0.toFixed(2) + ' Hz';
    if (rmsEl) rmsEl.textContent = r.rms.toFixed(3) + ' g';
    var hayEvento = evento > 0.25;
    fig.classList.toggle('alerta', hayEvento);
    if (estadoEl) estadoEl.textContent = hayEvento ? (T.evento || 'evento') : (T.reposo || '—');
  }

  function paso(ahora) {
    pedido = 0;
    if (pausado || !visible || document.hidden) { ultimo = 0; return; }
    if (ultimo) {
      resto += Math.min(0.25, (ahora - ultimo) / 1000) * FS;   // tiempo real, sin saltos enormes
      var nuevas = Math.floor(resto);
      resto -= nuevas;
      if (nuevas > 0) {
        buf.copyWithin(0, nuevas);
        for (var i = N - nuevas; i < N; i++) buf[i] = muestra();
      }
    }
    ultimo = ahora;
    if (cuadro++ % 30 === 0) leerColores();                      // sigue el cambio de tema
    var r = dibujar();
    if (cuadro % 6 === 0) datos(r);
    pedir();
  }
  function pedir() { if (!pedido) pedido = requestAnimationFrame(paso); }

  function botonTexto() {
    if (!boton) return;
    boton.textContent = pausado ? (T.reanudar || 'Reanudar') : (T.pausa || 'Pausar');
    boton.setAttribute('aria-pressed', String(pausado));
  }
  if (boton) {
    boton.addEventListener('click', function () { pausado = !pausado; botonTexto(); if (!pausado) pedir(); });
  }

  if ('IntersectionObserver' in window) {
    new IntersectionObserver(function (e) { visible = e[0].isIntersecting; if (visible) pedir(); }).observe(fig);
  }
  document.addEventListener('visibilitychange', function () { if (!document.hidden) pedir(); });
  window.addEventListener('resize', function () { if (pausado) { leerColores(); datos(dibujar()); } });

  fig.hidden = false;
  leerColores();
  datos(dibujar());
  botonTexto();
  if (!pausado) pedir();
  fig.setAttribute('data-listo', '');
})();

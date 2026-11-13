// Funciones del CV en el navegador. Todo es mejora progresiva: sin JavaScript
// la página se ve completa y estos controles quedan ocultos (atributo hidden).
//   1. Barra de secciones: resalta la sección visible.
//   2. Tema claro/oscuro manual (la elección se guarda en este navegador).
//   3. Copiar correo y compartir el enlace.
//   4. Buscador y filtro por competencia: marca dónde aparece un término
//      (experiencia, proyectos, publicaciones, formación, áreas) y atenúa el resto.
//      La búsqueda queda en la dirección (?q=python) para poder compartirla.
(function () {
  'use strict';

  var T = {};
  try { T = JSON.parse(document.getElementById('cv-textos').textContent); } catch (e) { /* sin textos */ }
  var $ = function (s, r) { return (r || document).querySelector(s); };
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };
  var fmt = function (s) { var a = arguments, i = 1; return String(s || '').replace(/%[sd]/g, function () { return a[i++]; }); };

  // ── Aviso breve (correo copiado, enlace copiado) ──
  var avisoTimer;
  function avisar(texto) {
    var el = $('#aviso');
    if (!el) return;
    el.textContent = texto;
    clearTimeout(avisoTimer);
    avisoTimer = setTimeout(function () { el.textContent = ''; }, 2600);
  }

  function copiar(texto) {
    if (navigator.clipboard && window.isSecureContext) return navigator.clipboard.writeText(texto);
    return new Promise(function (ok, mal) {
      var t = document.createElement('textarea');
      t.value = texto; t.setAttribute('readonly', ''); t.style.position = 'fixed'; t.style.opacity = '0';
      document.body.appendChild(t); t.select();
      var hecho = false;
      try { hecho = document.execCommand('copy'); } catch (e) { /* sin permiso */ }
      document.body.removeChild(t);
      hecho ? ok() : mal();
    });
  }

  // ── 1. Sección visible en la barra ──
  (function () {
    if (!('IntersectionObserver' in window)) return;
    var links = {};
    $$('.nav-inner a').forEach(function (a) { links[a.hash.slice(1)] = a; });
    var actual = null;
    var io = new IntersectionObserver(function (entradas) {
      entradas.forEach(function (en) {
        if (!en.isIntersecting || !links[en.target.id]) return;
        if (actual) { actual.classList.remove('active'); actual.removeAttribute('aria-current'); }
        actual = links[en.target.id];
        actual.classList.add('active');
        actual.setAttribute('aria-current', 'location');
        var barra = actual.parentNode, r = actual.getBoundingClientRect(), b = barra.getBoundingClientRect();
        if (r.left < b.left || r.right > b.right) barra.scrollLeft += r.left - b.left - 20;
      });
    }, { rootMargin: '-45% 0px -50% 0px' });
    $$('main section[id]').forEach(function (s) { io.observe(s); });
  })();

  // ── 2. Tema ──
  (function () {
    var boton = $('#tema');
    if (!boton) return;
    var raiz = document.documentElement;
    var sistema = window.matchMedia ? window.matchMedia('(prefers-color-scheme: dark)') : null;
    function efectivo() { return raiz.getAttribute('data-theme') || (sistema && sistema.matches ? 'dark' : 'light'); }
    function pintar() {
      var t = efectivo(), texto = t === 'dark' ? T.tema_claro : T.tema_oscuro;
      boton.setAttribute('data-actual', t);
      boton.setAttribute('aria-label', texto);
      boton.title = texto;
      var meta = $$('meta[name="theme-color"]');
      meta.forEach(function (m) { m.setAttribute('content', t === 'dark' ? '#0f1521' : '#1b2a4a'); m.removeAttribute('media'); });
    }
    boton.addEventListener('click', function () {
      var nuevo = efectivo() === 'dark' ? 'light' : 'dark';
      raiz.setAttribute('data-theme', nuevo);
      try { localStorage.setItem('tema', nuevo); } catch (e) { /* no se guarda, pero cambia */ }
      pintar();
    });
    if (sistema && sistema.addEventListener) sistema.addEventListener('change', pintar);
    pintar();
    boton.hidden = false;
  })();

  // ── 3. Copiar correo y compartir ──
  $$('[data-copiar]').forEach(function (b) {
    b.hidden = false;
    b.addEventListener('click', function () {
      var texto = b.getAttribute('data-copiar');
      copiar(texto).then(function () { avisar(fmt(T.copiado, texto)); }, function () { avisar(T.no_copiado); });
    });
  });

  (function () {
    var b = $('#compartir');
    if (!b) return;
    b.hidden = false;
    b.addEventListener('click', function () {
      var datos = { title: T.titulo || document.title, url: T.url || location.href };
      if (navigator.share) {
        navigator.share(datos).catch(function () { /* cancelado */ });
      } else {
        copiar(datos.url).then(function () { avisar(T.enlace_copiado + ': ' + datos.url); }, function () { avisar(T.no_copiado); });
      }
    });
  })();

  // ── 4. Buscador y filtro por competencia ──
  (function () {
    var caja = $('#buscador'), input = $('#q'), limpiar = $('#q-limpiar'), estado = $('#q-estado');
    if (!caja || !input) return;

    // Sin acentos ni mayúsculas: «máquina» encuentra «maquina».
    function norm(s) { return String(s).normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase(); }

    // Unidades que se pueden encontrar, con la sección a la que pertenecen.
    var unidades = [];
    function registrar(sel, seccion) {
      $$(sel).forEach(function (el) {
        el.setAttribute('data-buscable', '');
        unidades.push({ el: el, seccion: seccion, texto: norm(el.textContent) });
      });
    }
    registrar('#exp .entry li', 'exp');
    registrar('#projects .card', 'projects');
    registrar('#skills .chips li', 'skills');
    registrar('#pubs .pubs > li, #pubs .talk', 'pubs');
    registrar('#edu .entry .chips li, #edu .entry .note, #edu .two-col li', 'edu');
    registrar('#areas .area', 'areas');

    // Un término que no es palabra completa (p. ej. «C» o «R») daría falsos positivos:
    // se busca como palabra cuando es corto y como fragmento cuando es largo.
    function patron(termino) {
      var t = norm(termino).replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
      return t.length <= 3 ? new RegExp('(^|[^a-z0-9])' + t + '($|[^a-z0-9+#])') : new RegExp(t);
    }

    // Variantes de una competencia: «Linux / Ubuntu Server» → Linux, Ubuntu Server, Ubuntu;
    // «C/C++ (embebidos)» → C/C++, C++; «GitHub Actions (CI)» → GitHub Actions;
    // «APIs REST» → también «API REST» y «REST API». Así el filtro encuentra cómo se
    // escribe en la experiencia («servidores Ubuntu»), no solo la etiqueta exacta.
    function variantes(etiqueta) {
      var base = etiqueta.replace(/\s*\([^)]*\)\s*/g, ' ').trim();
      var partes = base.split(/\s+\/\s+/);
      if (/^C\/C\+\+/.test(base)) partes = ['C/C++', 'C++'];
      var extra = [];
      partes.forEach(function (v) {
        var sin = v.replace(/\s+(Server|Desktop)$/i, '');
        if (sin !== v) extra.push(sin);
        if (/^APIs? REST$|^REST APIs?$/i.test(v)) extra.push('API REST', 'APIs REST', 'REST API');
      });
      return partes.concat(extra).filter(Boolean);
    }

    var abiertos = [];
    function aplicar(consulta, opciones) {
      opciones = opciones || {};
      var q = consulta.trim();
      unidades.forEach(function (u) { u.el.classList.remove('coincide'); });
      abiertos.forEach(function (d) { d.open = false; });
      abiertos = [];
      $$('#skills .filtro').forEach(function (b) { b.setAttribute('aria-pressed', String(!!opciones.chip && b === opciones.chip)); });

      if (!q) {
        document.body.classList.remove('filtrando');
        estado.textContent = '';
        limpiar.hidden = true;
        actualizarUrl('');
        return;
      }
      // Desde un chip: cualquiera de sus variantes. Escribiendo: todas las palabras.
      var pruebas = opciones.variantes
        ? [opciones.variantes.map(patron)]
        : norm(q).split(/\s+/).filter(Boolean).map(function (p) { return [patron(p)]; });

      var porSeccion = {}, total = 0;
      unidades.forEach(function (u) {
        var ok = pruebas.every(function (grupo) { return grupo.some(function (re) { return re.test(u.texto); }); });
        if (!ok) return;
        u.el.classList.add('coincide');
        porSeccion[u.seccion] = (porSeccion[u.seccion] || 0) + 1;
        total++;
        // Abre los desplegables (asignaturas, áreas) que tienen coincidencias.
        var d = u.el.closest('details');
        if (d && !d.open) { d.open = true; abiertos.push(d); }
      });

      document.body.classList.add('filtrando');
      limpiar.hidden = false;
      if (!total) {
        estado.textContent = fmt(T.sin_resultados, q);
      } else {
        var detalle = Object.keys(T.secciones || {}).filter(function (k) { return porSeccion[k]; })
          .map(function (k) { return T.secciones[k] + ' ' + porSeccion[k]; }).join(' · ');
        estado.textContent = fmt(T.resultados, q, total) + ' — ' + detalle;
      }
      actualizarUrl(q);
    }

    function actualizarUrl(q) {
      if (!window.history || !history.replaceState) return;
      var u = new URL(location.href);
      if (q) u.searchParams.set('q', q); else u.searchParams.delete('q');
      history.replaceState(null, '', u.pathname + u.search + u.hash);
    }

    var espera;
    input.addEventListener('input', function () {
      clearTimeout(espera);
      espera = setTimeout(function () { aplicar(input.value); }, 120);
    });
    input.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') { input.value = ''; aplicar(''); }
    });
    limpiar.addEventListener('click', function () { input.value = ''; aplicar(''); input.focus(); });

    // Competencias como botones de filtro.
    $$('#skills .chips li').forEach(function (li) {
      var etiqueta = li.textContent.trim();
      var b = document.createElement('button');
      b.type = 'button';
      b.className = 'filtro';
      b.textContent = etiqueta;
      b.setAttribute('aria-pressed', 'false');
      b.title = fmt(T.filtrar, etiqueta);
      li.textContent = '';
      li.appendChild(b);
      b.addEventListener('click', function () {
        if (b.getAttribute('aria-pressed') === 'true') { input.value = ''; aplicar(''); return; }
        input.value = etiqueta;
        aplicar(etiqueta, { chip: b, variantes: variantes(etiqueta) });
        caja.scrollIntoView({ behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth', block: 'start' });
      });
    });

    caja.hidden = false;

    // Búsqueda que llega en la dirección (?q=…), p. ej. un enlace compartido.
    var inicial = new URLSearchParams(location.search).get('q');
    if (inicial) {
      input.value = inicial.slice(0, 80);
      var chip = $$('#skills .filtro').filter(function (b) { return norm(b.textContent) === norm(inicial); })[0];
      aplicar(input.value, chip ? { chip: chip, variantes: variantes(chip.textContent) } : null);
    }
  })();
})();

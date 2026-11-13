// Resalta en la barra de navegación la sección visible.
// Va en un archivo aparte (no en línea) para que la CSP pueda ser script-src 'self'.
(function () {
  if (!('IntersectionObserver' in window)) return;
  var links = {};
  document.querySelectorAll('.nav-inner a').forEach(function (a) { links[a.hash.slice(1)] = a; });
  var current = null;
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (en) {
      if (!en.isIntersecting || !links[en.target.id]) return;
      if (current) { current.classList.remove('active'); current.removeAttribute('aria-current'); }
      current = links[en.target.id];
      current.classList.add('active');
      current.setAttribute('aria-current', 'location');
      var bar = current.parentNode, r = current.getBoundingClientRect(), b = bar.getBoundingClientRect();
      if (r.left < b.left || r.right > b.right) bar.scrollLeft += r.left - b.left - 20;
    });
  }, { rootMargin: '-45% 0px -50% 0px' });
  document.querySelectorAll('main section[id]').forEach(function (s) { io.observe(s); });
})();

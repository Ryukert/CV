// Aplica el tema elegido (claro u oscuro) antes de pintar la página, para que no
// parpadee. Se carga sin defer en <head>; es un archivo aparte porque la CSP no
// permite scripts en línea. Sin una elección guardada, manda el tema del sistema.
(function () {
  try {
    var t = localStorage.getItem('tema');
    if (t === 'light' || t === 'dark') document.documentElement.setAttribute('data-theme', t);
  } catch (e) { /* almacenamiento bloqueado: se queda el del sistema */ }
})();

'use client';
// Carga los scripts del navegador (public/assets/cv.js: buscador, filtro, tema, copiar,
// compartir; monitor.js: el monitor de señal del encabezado) cuando React ya terminó de
// hidratar la página. Si cambiaran el DOM antes, React vería un HTML distinto al del servidor
// y lo volvería a pintar, borrando esos cambios. Sin JavaScript no corre nada y la página
// queda completa.
//
// La CSP exige Trusted Types: la URL de cada script pasa por la política «cv», que solo acepta
// archivos del propio sitio en /assets/.
import { useEffect } from 'react';

type Politica = { createScriptURL: (url: string) => string };
let politica: Politica | null | undefined;

function urlDeScript(src: string): string {
  if (!/^\/assets\/[\w.-]+\.js(\?v=[0-9a-f]+)?$/.test(src)) throw new Error('Script no permitido: ' + src);
  if (politica === undefined) {
    const tt = (window as unknown as { trustedTypes?: { createPolicy: (n: string, r: Politica) => Politica } }).trustedTypes;
    politica = tt ? tt.createPolicy('cv', { createScriptURL: (url) => url }) : null;
  }
  return politica ? politica.createScriptURL(src) : src;
}

export default function ArrancarCv({ srcs, nonce }: { srcs: string[]; nonce?: string }) {
  useEffect(() => {
    for (const src of srcs) {
      if (document.querySelector(`script[data-cv="${src}"]`)) continue;   // una sola vez
      const s = document.createElement('script');
      s.src = urlDeScript(src);
      s.async = false;          // en orden: cv.js antes que monitor.js
      s.dataset.cv = src;
      if (nonce) s.nonce = nonce;
      document.body.appendChild(s);
    }
  }, [srcs, nonce]);
  return null;
}

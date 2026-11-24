'use client';
// Carga los scripts del navegador (public/assets/cv.js: buscador, filtro, tema, copiar,
// compartir; monitor.js: el monitor de señal del encabezado) cuando React ya terminó de
// hidratar la página. Si cambiaran el DOM antes, React vería un HTML distinto al del servidor
// y lo volvería a pintar, borrando esos cambios. Sin JavaScript no corre nada y la página
// queda completa.
import { useEffect } from 'react';

export default function ArrancarCv({ srcs, nonce }: { srcs: string[]; nonce?: string }) {
  useEffect(() => {
    for (const src of srcs) {
      if (document.querySelector(`script[data-cv="${src}"]`)) continue;   // una sola vez
      const s = document.createElement('script');
      s.src = src;
      s.async = false;          // en orden: cv.js antes que monitor.js
      s.dataset.cv = src;
      if (nonce) s.nonce = nonce;
      document.body.appendChild(s);
    }
  }, [srcs, nonce]);
  return null;
}

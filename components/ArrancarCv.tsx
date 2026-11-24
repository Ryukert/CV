'use client';
// Carga public/assets/cv.js (buscador, filtro, tema, copiar, compartir) cuando React ya
// terminó de hidratar la página. Si cv.js cambiara el DOM antes, React vería un HTML
// distinto al del servidor y lo volvería a pintar, borrando esos cambios.
// Sin JavaScript no corre nada y la página queda completa.
import { useEffect } from 'react';

export default function ArrancarCv({ src, nonce }: { src: string; nonce?: string }) {
  useEffect(() => {
    if (document.querySelector('script[data-cv]')) return;   // una sola vez
    const s = document.createElement('script');
    s.src = src;
    s.dataset.cv = '';
    if (nonce) s.nonce = nonce;
    document.body.appendChild(s);
  }, [src, nonce]);
  return null;
}

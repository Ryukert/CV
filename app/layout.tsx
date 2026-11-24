import type { ReactNode } from 'react';
import { headers } from 'next/headers';
import { asset } from '@/lib/cv.ts';
import ArrancarCv from '@/components/ArrancarCv.tsx';

export default async function Layout({ children }: { children: ReactNode }) {
  const h = await headers();
  const lang = h.get('x-cv-lang') === 'en' ? 'en' : 'es';
  const nonce = h.get('x-nonce') ?? undefined;

  return (
    // tema.js pone data-theme en <html> antes de hidratar: es intencional, no un error.
    <html lang={lang} suppressHydrationWarning>
      <head>
        <meta name="theme-color" content="#1b2a4a" media="(prefers-color-scheme: light)" />
        <meta name="theme-color" content="#0f1521" media="(prefers-color-scheme: dark)" />
        <meta name="color-scheme" content="light dark" />
        <link rel="icon" href={asset('favicon.svg')} type="image/svg+xml" />
        <link rel="apple-touch-icon" href={asset('apple-touch-icon.png')} />
        <link rel="preload" href="/assets/fonts/ibm-plex-sans-400.woff2" as="font" type="font/woff2" crossOrigin="" />
        {/* tema.js va antes del CSS y sin defer: aplica el tema guardado antes de pintar. */}
        <script src={asset('tema.js')} nonce={nonce} />
        <link rel="stylesheet" href={asset('style.css')} />
      </head>
      <body>
        {children}
        <ArrancarCv src={asset('cv.js')} nonce={nonce} />
      </body>
    </html>
  );
}

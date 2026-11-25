// Corre antes de cada petición (en Next 16 «proxy» reemplaza a «middleware»).
//
// - Solo GET y HEAD: el sitio no recibe datos (lo demás responde 405).
// - CSP estricta con un nonce distinto en cada respuesta: Next.js pone scripts en línea
//   para arrancar la página y solo se ejecutan los que llevan ese nonce. Nada de
//   'unsafe-inline'; estilos, fuentes e imágenes solo del propio sitio.
// - Le dice al layout el idioma de la página (para <html lang>).
import { NextResponse, type NextRequest } from 'next/server';

const SEGURIDAD: Record<string, string> = {
  'X-Content-Type-Options': 'nosniff',
  'Referrer-Policy': 'strict-origin-when-cross-origin',
  'X-Frame-Options': 'DENY',
  // browsing-topics reemplaza a interest-cohort (FLoC, que Chrome abandonó).
  'Permissions-Policy': 'camera=(), microphone=(), geolocation=(), payment=(), usb=(), browsing-topics=()',
  'Cross-Origin-Opener-Policy': 'same-origin',
};

export function proxy(request: NextRequest) {
  if (request.method !== 'GET' && request.method !== 'HEAD') {
    return new NextResponse('Método no permitido\n', {
      status: 405,
      headers: { Allow: 'GET, HEAD', 'Content-Type': 'text/plain; charset=utf-8', ...SEGURIDAD },
    });
  }

  const nonce = btoa(crypto.randomUUID());
  // En desarrollo, Next.js necesita eval para recargar en caliente.
  const evalDev = process.env.NODE_ENV === 'development' ? " 'unsafe-eval'" : '';
  const csp = [
    "default-src 'self'",
    `script-src 'self' 'nonce-${nonce}'${evalDev}`,
    "style-src 'self'",
    "img-src 'self' data:",
    "font-src 'self'",
    "connect-src 'self'",
    "object-src 'none'",
    "base-uri 'self'",
    "form-action 'none'",
    "frame-ancestors 'none'",
    // Trusted Types: el navegador rechaza meter HTML o URLs de script por innerHTML, src, etc.
    // salvo a través de una política con nombre. Solo existen «cv» (components/ArrancarCv.tsx,
    // que solo acepta scripts de /assets/) y las de Next.js.
    "require-trusted-types-for 'script'",
    "trusted-types cv nextjs nextjs#bundler",
  ].join('; ');

  const ruta = request.nextUrl.pathname;
  const lang = ruta === '/en' || ruta.startsWith('/en/') ? 'en' : 'es';

  const entrada = new Headers(request.headers);
  entrada.set('x-nonce', nonce);
  entrada.set('x-cv-lang', lang);
  entrada.set('Content-Security-Policy', csp);   // Next.js toma de aquí el nonce

  const res = NextResponse.next({ request: { headers: entrada } });
  res.headers.set('Content-Security-Policy', csp);
  for (const [k, v] of Object.entries(SEGURIDAD)) res.headers.set(k, v);
  if (ruta === '/' || ruta === '/en') res.headers.set('Content-Language', lang);
  return res;
}

export const config = {
  // Los archivos estáticos no pasan por aquí (llevan sus encabezados en next.config.ts).
  matcher: ['/((?!assets/|_next/static|_next/image|favicon.ico).*)'],
};

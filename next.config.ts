import type { NextConfig } from 'next';

const config: NextConfig = {
  poweredByHeader: false,   // sin «X-Powered-By: Next.js»
  reactStrictMode: true,
  // Los archivos de /assets llevan ?v=<hash> según su contenido: se guardan un año.
  async headers() {
    return [
      {
        source: '/assets/:ruta*',
        headers: [
          { key: 'Cache-Control', value: 'public, max-age=31536000, immutable' },
          { key: 'X-Content-Type-Options', value: 'nosniff' },
        ],
      },
      {
        source: '/assets/:archivo*.pdf',
        headers: [{ key: 'Cache-Control', value: 'public, max-age=86400, must-revalidate' }],
      },
    ];
  },
};

export default config;

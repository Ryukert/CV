// GET /api/cv.json?lang=es|en — el CV completo en JSON, legible desde cualquier sitio.
import { cvData } from '@/lib/cv.ts';

export function GET(request: Request) {
  const lang = new URL(request.url).searchParams.get('lang') === 'en' ? 'en' : 'es';
  const { ui: _textosInterfaz, profile, ...cv } = cvData(lang);   // los textos de la interfaz no son parte del CV
  // El teléfono no se publica en la web ni en la API (lo recolectan bots para spam): solo va en el PDF.
  const { phone: _telefono, ...perfil } = profile;
  return new Response(JSON.stringify({ ...cv, profile: perfil }, null, 4), {
    headers: {
      'Content-Type': 'application/json; charset=utf-8',
      'Access-Control-Allow-Origin': '*',
      // Una hora en el navegador; un día en la CDN, sirviendo la copia anterior mientras se renueva.
      'Cache-Control': 'public, max-age=3600, s-maxage=86400, stale-while-revalidate=604800',
      Vary: 'Accept-Encoding',
    },
  });
}

import { CV_UPDATED, url } from '@/lib/cv.ts';

const xml = (s: string) => s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');

export async function GET() {
  const es = xml(await url('/'));
  const en = xml(await url('/en'));
  let cuerpo = '<?xml version="1.0" encoding="UTF-8"?>\n'
    + '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">\n';
  for (const loc of [es, en]) {
    cuerpo += `  <url>\n    <loc>${loc}</loc>\n`
      + `    <xhtml:link rel="alternate" hreflang="es" href="${es}"/>\n`
      + `    <xhtml:link rel="alternate" hreflang="en" href="${en}"/>\n`
      // Fecha real de la última actualización del CV, no la del día de la consulta.
      + `    <lastmod>${CV_UPDATED}</lastmod>\n    <changefreq>monthly</changefreq>\n  </url>\n`;
  }
  cuerpo += '</urlset>\n';
  return new Response(cuerpo, {
    headers: { 'Content-Type': 'application/xml; charset=utf-8', 'Cache-Control': 'public, max-age=86400' },
  });
}

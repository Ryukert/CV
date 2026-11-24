// Pruebas de humo del CV: levanta la versión compilada (next start) y revisa cada ruta
// como la vería un navegador.
//
//   npm run build && npm test
//
// Sale con código 1 si alguna prueba falla.
import { spawn } from 'node:child_process';
import { createHash } from 'node:crypto';
import { existsSync, readdirSync, readFileSync } from 'node:fs';
import net from 'node:net';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import { parseHTML } from 'linkedom';
import { SITIO_PUBLICO, siteUrl } from '../lib/cv-core.ts';
import { compartido, cvData } from '../lib/datos-node.ts';
import { cvRendercv, huella, rcvMd } from '../lib/rendercv-core.ts';

const RAIZ = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const PUERTO = Number(process.env.PUERTO || 8098);
const BASE = `http://127.0.0.1:${PUERTO}`;

if (!existsSync(path.join(RAIZ, '.next', 'BUILD_ID'))) {
  console.error('Falta compilar: npm run build');
  process.exit(1);
}
const srv = spawn(process.execPath, [path.join(RAIZ, 'node_modules', 'next', 'dist', 'bin', 'next'), 'start', '-p', String(PUERTO), '-H', '127.0.0.1'],
  { cwd: RAIZ, stdio: 'ignore' });
process.on('exit', () => srv.kill());
for (let i = 0; i < 100; i++) {
  const listo = await new Promise((r) => { const s = net.connect(PUERTO, '127.0.0.1', () => { s.end(); r(true); }).on('error', () => r(false)); });
  if (listo) break;
  await new Promise((r) => setTimeout(r, 100));
}

let total = 0;
let fallas = 0;
function ok(cond: boolean, texto: string) {
  total++;
  if (!cond) fallas++;
  console.log((cond ? '  ok    ' : '  FALLA ') + texto);
}

async function pedir(ruta: string, metodo = 'GET') {
  const r = await fetch(BASE + ruta, { method: metodo, redirect: 'manual' });
  return { status: r.status, h: r.headers, body: await r.text() };
}

const cspNonce = (csp: string) => /'nonce-([^']+)'/.exec(csp)?.[1] ?? '';

// ── Páginas del CV en los dos idiomas ──
for (const [ruta, lang] of [['/', 'es'], ['/en', 'en']] as const) {
  const r = await pedir(ruta);
  const d = cvData(lang);
  ok(r.status === 200 && (r.h.get('content-type') ?? '').startsWith('text/html'), `${ruta} responde 200 en HTML`);
  const csp = r.h.get('content-security-policy') ?? '';
  ok(/script-src 'self' 'nonce-[^']+'/.test(csp) && csp.includes("frame-ancestors 'none'") && csp.includes("style-src 'self'")
    && !csp.includes('unsafe-inline') && !csp.includes('unsafe-eval'), `${ruta} trae CSP estricta con nonce (sin unsafe-inline ni unsafe-eval)`);
  const otra = await pedir(ruta);
  ok(cspNonce(csp) !== '' && cspNonce(csp) !== cspNonce(otra.h.get('content-security-policy') ?? ''), `${ruta}: el nonce cambia en cada respuesta`);
  ok(r.h.get('x-frame-options') === 'DENY' && r.h.has('permissions-policy') && r.h.get('x-content-type-options') === 'nosniff', `${ruta} trae X-Frame-Options, Permissions-Policy y nosniff`);
  ok(!r.h.has('x-powered-by'), `${ruta} no anuncia con qué está hecho (sin X-Powered-By)`);
  ok(r.h.get('content-language') === lang, `${ruta} declara Content-Language: ${lang}`);

  // React escribe algunos atributos en camelCase (hrefLang, dateTime…). En HTML da igual,
  // pero los selectores de linkedom distinguen mayúsculas: se pasan a minúsculas.
  const html = r.body.replace(/ (hrefLang|dateTime|charSet|crossOrigin|noModule|fetchPriority)=/g, (m) => m.toLowerCase());
  const { document: doc } = parseHTML(html);
  const q = (s: string) => [...doc.querySelectorAll(s)] as Element[];
  ok(doc.documentElement.getAttribute('lang') === lang, `${ruta} declara lang="${lang}"`);
  ok(q('h1').length === 1, `${ruta} tiene un solo h1`);
  ok(q('head title').length === 1 && q('head meta[name="description"]').length === 1 && q('head link[rel="canonical"]').length === 1,
    `${ruta}: título, descripción y canonical en el <head>`);

  // Todo script en línea que se ejecuta lleva el nonce de la CSP; los bloques de datos no se ejecutan.
  const enLinea = q('script:not([src])').filter((s) => !['application/ld+json', 'application/json'].includes(s.getAttribute('type') ?? ''));
  ok(enLinea.every((s) => s.getAttribute('nonce') === cspNonce(csp)), `${ruta}: los ${enLinea.length} scripts en línea llevan el nonce de la CSP`);
  ok(q('[style], [onclick], [onload], style').length === 0, `${ruta} sin estilos ni manejadores en línea (los bloquearía la CSP)`);
  ok(q('link[href*="fonts.googleapis"], link[href*="gstatic"]').length === 0, `${ruta} no carga nada de Google Fonts`);
  ok(q('img, picture').length === 0 && !r.body.includes('/assets/foto'), `${ruta} no muestra foto`);

  // Todo recurso propio que pide la página existe en public/assets/.
  const faltan: string[] = [];
  for (const n of q('[href], [src]')) {
    for (const at of ['href', 'src']) {
      const u = n.getAttribute(at) ?? '';
      if (u.startsWith('/assets/') && !existsSync(path.join(RAIZ, 'public', u.split('?')[0]))) faltan.push(u);
    }
  }
  ok(faltan.length === 0, `${ruta}: existen todos los archivos de /assets/ que enlaza` + (faltan.length ? ` (faltan ${faltan.join(', ')})` : ''));
  ok((r.body.match(/\/assets\/(style\.css|cv\.js|tema\.js|og\.jpg|cv-[\w-]+\.pdf)\?v=[0-9a-f]{8}/g) ?? []).length >= 6,
    `${ruta} versiona CSS, JS, imagen para redes y PDF por contenido (?v=hash)`);

  // Funciones con JavaScript: ocultas de inicio (sin JS la página queda completa) y con sus textos.
  for (const id of ['buscador', 'tema', 'compartir']) {
    if (!doc.getElementById(id)?.hasAttribute('hidden')) ok(false, `${ruta}: #${id} existe y empieza oculto`);
  }
  ok(q('button[data-copiar][hidden]').length === 2, `${ruta}: dos botones «Copiar correo», ocultos sin JavaScript`);
  let tj: Record<string, unknown> | null = null;
  try { tj = JSON.parse(doc.getElementById('cv-textos')?.textContent ?? ''); } catch { /* inválido */ }
  ok(!!tj && 'buscar' in tj && 'secciones' in tj && String(tj.url).startsWith('http'), `${ruta}: textos de la interfaz en JSON válido`);
  const tema = q('script[src*="tema.js"]');
  ok(r.body.indexOf('tema.js') < r.body.search(/rel="stylesheet" href="\/assets\/style\.css/) && tema.length === 1 && !tema[0].hasAttribute('defer') && !tema[0].hasAttribute('async'),
    `${ruta}: tema.js va antes del CSS y sin defer (no parpadea el tema)`);

  let ld: Record<string, unknown> | null = null;
  try { ld = JSON.parse(q('script[type="application/ld+json"]')[0]?.textContent ?? ''); } catch { /* inválido */ }
  ok(!!ld && ld['@type'] === 'Person' && !String(ld.url).includes('github.com'), `${ruta}: JSON-LD Person válido con la URL del CV`);

  ok(q('a[rel="me noopener"]').length === 3, `${ruta}: GitHub, LinkedIn y ORCID con rel="me"`);
  const otro = lang === 'es' ? 'en' : 'es';
  ok(q(`a[hreflang="${otro}"][lang="${otro}"]`).length >= 1, `${ruta}: el enlace al otro idioma declara lang y hreflang`);
  ok(q('meta[property="og:image:width"]').length === 1 && q('meta[property="og:image:alt"]').length === 1, `${ruta}: Open Graph con medidas y texto alternativo de la imagen`);

  // Contenido: cada proyecto y cada sección del menú aparecen.
  const titulos = q('#projects .card h3').map((h) => h.textContent);
  for (const p of d.projects) {
    const [titulo] = d.project_text[p.key];
    if (!titulos.includes(titulo)) ok(false, `${ruta} muestra el proyecto «${titulo}»`);
  }
  ok(d.projects.length === Object.keys(d.project_text).length, `${ruta}: todos los proyectos tienen texto (${d.projects.length})`);
  for (const id of Object.keys(d.nav)) {
    if (q(`section#${id}`).length !== 1) ok(false, `${ruta}: existe la sección #${id} del menú`);
  }
  ok(q('#projects .card .fecha time[datetime]').length === d.projects.length, `${ruta}: cada proyecto muestra su fecha`);
  ok(q('#skills .chips li svg.tico').length === Object.values(d.skills).flat().length, `${ruta}: cada competencia lleva ícono`);
}

// ── Los dos idiomas tienen la misma estructura ──
const es = cvData('es');
const en = cvData('en');
ok(JSON.stringify(Object.keys(es)) === JSON.stringify(Object.keys(en)), 'español e inglés tienen las mismas claves');
ok(es.experience[0].groups.length === en.experience[0].groups.length && Object.keys(es.skills).length === Object.keys(en.skills).length,
  'misma cantidad de grupos de experiencia y de competencias');
ok(JSON.stringify(Object.keys(es.project_text)) === JSON.stringify(Object.keys(en.project_text)), 'los mismos proyectos en los dos idiomas');
ok(compartido.projects.every((p) => /^\d{4}-(0[1-9]|1[0-2])$/.test(p.date)), 'cada proyecto tiene fecha AAAA-MM');

// ── API JSON ──
let r = await pedir('/api/cv.json?lang=en');
const j = JSON.parse(r.body);
ok(r.status === 200 && j.lang === 'en', '/api/cv.json?lang=en devuelve el CV en inglés');
ok(r.h.get('access-control-allow-origin') === '*', '/api/cv.json se puede leer desde otros sitios (CORS)');
ok((r.h.get('cache-control') ?? '').includes('s-maxage=86400'), '/api/cv.json se guarda un día en la CDN');
ok(j.profile?.name === compartido.profile.name, '/api/cv.json trae el perfil');
ok(!('ui' in j) && !('photo' in (j.profile ?? {})), '/api/cv.json sin textos de interfaz ni foto');
r = await pedir('/api/cv.json?lang=xx');
ok(JSON.parse(r.body).lang === 'es', 'un idioma desconocido cae en español');

// ── Rutas auxiliares ──
r = await pedir('/health');
const h = JSON.parse(r.body);
ok(r.status === 200 && h.status === 'ok' && h.cv_updated === compartido.updated && Object.keys(h).length === 2, '/health responde ok sin anunciar versiones');
r = await pedir('/sitemap.xml');
ok(r.status === 200 && r.body.includes(`<lastmod>${compartido.updated}</lastmod>`), `/sitemap.xml usa la fecha real de actualización (${compartido.updated})`);
ok(r.body.startsWith('<?xml') && (r.body.match(/<url>/g) ?? []).length === 2 && r.body.trimEnd().endsWith('</urlset>'), '/sitemap.xml es XML con las dos páginas');
r = await pedir('/robots.txt');
ok(r.status === 200 && r.body.includes('Sitemap: '), '/robots.txt apunta al sitemap');
r = await pedir('/no-existe');
ok(r.status === 404 && r.body.includes('noindex'), 'una ruta desconocida da 404 con noindex');
r = await pedir('/', 'POST');
ok(r.status === 405 && r.h.get('allow') === 'GET, HEAD', 'POST responde 405 (el sitio no recibe datos)');

// ── SITE_URL: la URL canónica no depende del Host de la petición ──
ok(siteUrl('/en', 'atacante.example', { SITE_URL: 'https://cv.ejemplo.dev' }) === 'https://cv.ejemplo.dev/en', 'con SITE_URL, las URL absolutas ignoran un Host falso');
ok(siteUrl('/', 'atacante.example', { VERCEL: '1' }) === SITIO_PUBLICO + '/' && SITIO_PUBLICO.startsWith('https://'), `en Vercel sin SITE_URL se usa la dirección pública con https (${SITIO_PUBLICO})`);
ok(siteUrl('/', 'cv.otro-dominio.mx').startsWith('https://'), 'fuera de localhost, la URL es https aunque el servidor no lo diga');

// ── Archivos ──
const assets = path.join(RAIZ, 'public', 'assets');
for (const f of ['cv-es.pdf', 'cv-en.pdf', 'cv-es-diseno.pdf', 'cv-en-design.pdf', 'og.jpg', 'cv.js', 'tema.js', 'fonts/ibm-plex-sans-400.woff2']) {
  if (!existsSync(path.join(assets, f))) ok(false, `existe public/assets/${f}`);
}
ok(!readdirSync(assets).some((f) => f.startsWith('foto')), 'no hay archivos de foto en public/assets/');
// Lo que style.css pide con url(…?v=hash) se guarda un año: la huella debe ser la del archivo actual.
const css = readFileSync(path.join(assets, 'style.css'), 'utf8');
const urlsCss = [...css.matchAll(/url\("([\w./-]+)\?v=([0-9a-f]{8})"\)/g)];
const malas = urlsCss.filter(([, f, v]) => !existsSync(path.join(assets, f))
  || createHash('md5').update(readFileSync(path.join(assets, f))).digest('hex').slice(0, 8) !== v).map(([, f]) => f);
ok(urlsCss.length >= 2 && malas.length === 0, 'style.css pide el fondo con la huella al día (?v=hash)' + (malas.length ? ` (cambiar: ${malas.join(', ')})` : ''));

// ── El PDF sencillo corresponde a los datos ──
// «npm run pdf» guarda la huella de los datos con que generó los PDF. Si cambiaron y no se
// regeneró, falla aquí.
let firma: { sitio?: string; huellas?: Record<string, string> } = {};
try { firma = JSON.parse(readFileSync(path.join(assets, 'cv-pdf.json'), 'utf8')); } catch { /* sin firma */ }
for (const l of ['es', 'en'] as const) {
  const alDia = firma.huellas?.[l] === huella(cvData(l), firma.sitio ?? '');
  ok(alDia, `public/assets/cv-${l}.pdf está al día con los datos` + (alDia ? '' : ' — corre: npm run pdf'));
}
const enPdf = JSON.stringify(cvRendercv(es));
ok(es.achievements.length >= 4 && enPdf.includes(es.sections.logros), 'los logros clave están en la web y en el PDF');
for (const p of es.projects) {
  if (p.pdf && !enPdf.includes(JSON.stringify(rcvMd(es.project_text[p.key][0])).slice(1, -1))) ok(false, `el PDF incluye el proyecto «${es.project_text[p.key][0]}»`);
}

let vercel: Record<string, unknown> | null = null;
try { vercel = JSON.parse(readFileSync(path.join(RAIZ, 'vercel.json'), 'utf8')); } catch { /* inválido */ }
ok(!!vercel && vercel.framework === 'nextjs' && !('routes' in vercel) && !('functions' in vercel), 'vercel.json válido, con Next.js y sin el runtime de PHP');
ok(!existsSync(path.join(RAIZ, 'api', 'index.php')) && !existsSync(path.join(RAIZ, 'src', 'data.php')), 'ya no queda código PHP del sitio');

console.log(`\nCV: ${total - fallas} de ${total} pruebas bien`);
process.exit(fallas ? 1 : 0);

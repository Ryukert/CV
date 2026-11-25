// Genera public/assets/og.jpg (1200×630), la imagen que muestran LinkedIn, WhatsApp, X, etc. al
// compartir el enlace del CV: el logotipo a la izquierda y, a la derecha, el cargo, la
// especialidad, las áreas de enfoque y la dirección del sitio, con la fuente y la paleta del sitio.
//
//   cd tests/navegador && npm ci     (una vez: instala puppeteer-core)
//   node herramientas/og.mjs
//
// Usa Chrome o Edge instalados (o la variable NAVEGADOR). Los textos salen de datos/es.json, así
// que si cambia el cargo basta con volver a correrlo.
import { existsSync, readFileSync, statSync } from 'node:fs';
import { createRequire } from 'node:module';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const RAIZ = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const require = createRequire(path.join(RAIZ, 'tests', 'navegador', 'package.json'));
const puppeteer = require('puppeteer-core');

const navegador = [process.env.NAVEGADOR, 'C:/Program Files/Google/Chrome/Application/chrome.exe',
  'C:/Program Files (x86)/Microsoft/Edge/Application/msedge.exe', 'C:/Program Files/Microsoft/Edge/Application/msedge.exe',
  '/usr/bin/google-chrome', '/usr/bin/chromium', '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome']
  .find((p) => p && existsSync(p));
if (!navegador) { console.error('No encontré Chrome ni Edge (variable NAVEGADOR)'); process.exit(1); }

const b64 = (f) => readFileSync(path.join(RAIZ, f)).toString('base64');
const es = JSON.parse(readFileSync(path.join(RAIZ, 'datos', 'es.json'), 'utf8'));
const sitio = 'cv-coral-pi.vercel.app';
const esc = (s) => s.replace(/&/g, '&amp;').replace(/</g, '&lt;');

const html = `<!doctype html><html><head><meta charset="utf-8"><style>
@font-face { font-family: Plex; font-weight: 400; src: url(data:font/woff2;base64,${b64('public/assets/fonts/ibm-plex-sans-400.woff2')}); }
@font-face { font-family: Plex; font-weight: 600; src: url(data:font/woff2;base64,${b64('public/assets/fonts/ibm-plex-sans-600.woff2')}); }
@font-face { font-family: Plex; font-weight: 700; src: url(data:font/woff2;base64,${b64('public/assets/fonts/ibm-plex-sans-700.woff2')}); }
* { box-sizing: border-box; margin: 0; }
body { width: 1200px; height: 630px; overflow: hidden; font-family: Plex, sans-serif; display: grid; grid-template-columns: 760px 440px; }
.logo { background: #fff; display: grid; place-items: center; position: relative; }
.logo img { width: 720px; height: auto; }
.datos {
  position: relative; color: #fff; padding: 56px 44px 0 44px;
  background:
    linear-gradient(rgba(255,255,255,.045) 1px, transparent 1px) 0 0 / 28px 28px,
    linear-gradient(90deg, rgba(255,255,255,.045) 1px, transparent 1px) 0 0 / 28px 28px,
    linear-gradient(160deg, #10284f 0%, #0d2142 45%, #061026 100%);
}
.etiqueta { font: 600 15px ui-monospace, Consolas, monospace; letter-spacing: 3px; text-transform: uppercase; color: #5cc27a; }
.nombre { margin-top: 14px; font-size: 40px; font-weight: 700; line-height: 1.08; letter-spacing: -.5px; }
.cargo { margin-top: 16px; font-size: 25px; font-weight: 600; line-height: 1.2; color: #e6bc4a; }
.sub { margin-top: 10px; font-size: 18px; line-height: 1.35; color: #c4d0e3; }
.areas { margin-top: 26px; display: flex; flex-wrap: wrap; gap: 8px; }
.areas span { font-size: 14.5px; font-weight: 600; padding: 6px 11px; border: 1px solid rgba(212,165,42,.55); border-radius: 999px; color: #eef3fb; background: rgba(255,255,255,.05); }
.url { position: absolute; left: 44px; bottom: 40px; font: 600 19px ui-monospace, Consolas, monospace; color: #fff; }
.url b { color: #5cc27a; font-weight: 600; }
.linea { position: absolute; left: 0; right: 0; bottom: 0; height: 10px; display: grid; grid-template-columns: 62fr 26fr 12fr; }
.linea i:nth-child(1) { background: #0d2142; } .linea i:nth-child(2) { background: #157a35; } .linea i:nth-child(3) { background: #d4a52a; }
</style></head><body>
<div class="logo"><img alt="" src="data:image/png;base64,${b64('img/fondo-original.png')}"></div>
<div class="datos">
  <p class="etiqueta">CV · ES / EN</p>
  <p class="nombre">Marco Antonio<br>Adame Rodríguez</p>
  <p class="cargo">${esc(es.role)}</p>
  <p class="sub">${esc(es.subrole)}</p>
  <div class="areas">${es.focus.map((f) => `<span>${esc(f.label)}</span>`).join('')}</div>
  <p class="url"><b>›</b> ${sitio}</p>
</div>
<div class="linea" style="grid-column: 1 / -1"><i></i><i></i><i></i></div>
</body></html>`;

const nav = await puppeteer.launch({ executablePath: navegador, headless: true, args: process.env.CI ? ['--no-sandbox'] : [] });
const p = await nav.newPage();
await p.setViewport({ width: 1200, height: 630, deviceScaleFactor: 1 });
await p.setContent(html, { waitUntil: 'load' });
await p.evaluate(() => document.fonts.ready);
const salida = path.join(RAIZ, 'public', 'assets', 'og.jpg');
await p.screenshot({ path: salida, type: 'jpeg', quality: 86, clip: { x: 0, y: 0, width: 1200, height: 630 } });
await nav.close();
console.log(`public/assets/og.jpg: 1200×630, ${Math.round(statSync(salida).size / 1024)} KB`);

// Genera el sprite de íconos del CV y su índice:
//   public/assets/iconos.svg  todos los trazos, cada uno en un <symbol id="…">
//   lib/iconos.ts             nombre de cada tecnología → [color, id del símbolo]
//
//   npm i --no-save simple-icons@16.30.0 && node herramientas/iconos.mjs
//
// La página no lleva los trazos: cada ícono es <svg><use href="/assets/iconos.svg?v=…#id"/></svg>.
// Así el HTML pesa mucho menos y el navegador guarda el sprite un año (cambia de URL si cambia).
//
// Logotipos: Simple Icons (CC0 1.0, https://simpleicons.org), copiados al sitio para no depender
// de otro servidor (la CSP sigue siendo default-src 'self'). Para agregar una tecnología:
// añadirla a SLUGS (nombre tal como aparece en datos/*.json → slug de Simple Icons) y volver a
// correr este script. Los íconos de contacto y los genéricos están más abajo, en este archivo.
import { writeFileSync } from 'node:fs';
import * as si from 'simple-icons';

const SLUGS = {
  'Python': 'python',
  'C/C++': 'cplusplus',
  'C++': 'cplusplus',
  'JavaScript': 'javascript',
  'TypeScript': 'typescript',
  'Node.js': 'nodedotjs',
  'PHP': 'php',
  'Bash': 'gnubash',
  'PyQt5': 'qt',
  'PySide6': 'qt',
  'React': 'react',
  'Preact': 'preact',
  'Next.js': 'nextdotjs',
  'Vite': 'vite',
  'Recharts': null,
  'Leaflet': 'leaflet',
  'OpenStreetMap': 'openstreetmap',
  'PWA': 'pwa',
  'Puppeteer': 'puppeteer',
  'NumPy': 'numpy',
  'Pandas': 'pandas',
  'SciPy': 'scipy',
  'Matplotlib': null,
  'Raspberry Pi': 'raspberrypi',
  'ESP32': 'espressif',
  'Arduino': 'arduino',
  'FastAPI': 'fastapi',
  'Supabase': 'supabase',
  'PostgreSQL': 'postgresql',
  'Firebase': 'firebase',
  'Vercel': 'vercel',
  'Ubuntu': 'ubuntu',
  'Linux': 'linux',
  'Docker': 'docker',
  'systemd': null,
  'Git': 'git',
  'GitHub Actions': 'githubactions',
  'Claude': 'claude',
  'Tailwind CSS': 'tailwindcss',
  'HTML': 'html5',
  'JSON': 'json',
};

// Íconos de contacto con relleno (toman el color del texto).
const RELLENO = {
  github: 'M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12',
  linkedin: 'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z',
};

// Íconos de trazo (caja de 24×24): contacto, áreas de enfoque y genéricos para tecnologías sin logotipo.
const TRAZO = {
  mail: '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/>',
  phone: '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
  orcid: '<circle cx="12" cy="12" r="10.5"/><path d="M8.5 10v7M8.5 7v.01M12 10v7h2.2a3.5 3.5 0 0 0 0-7H12z"/>',
  onda: '<path d="M2 12h3l3-8 4 16 4-12 2 4h4"/>',
  red: '<circle cx="5" cy="6" r="2"/><circle cx="5" cy="18" r="2"/><circle cx="19" cy="12" r="2"/><path d="m7 6.8 10 4.4M7 17.2l10-4.4"/>',
  chip: '<rect x="6" y="6" width="12" height="12" rx="1.5"/><path d="M9 2v4M15 2v4M9 18v4M15 18v4M2 9h4M2 15h4M18 9h4M18 15h4"/>',
  mapa: '<path d="M9 4 3 6v14l6-2 6 2 6-2V4l-6 2-6-2zM9 4v14M15 6v14"/>',
  datos: '<ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 5v14c0 1.7 3.6 3 8 3s8-1.3 8-3V5M4 12c0 1.7 3.6 3 8 3s8-1.3 8-3"/>',
  api: '<path d="M8 3H7a2 2 0 0 0-2 2v4a2 2 0 0 1-2 2 2 2 0 0 1 2 2v4a2 2 0 0 0 2 2h1M16 3h1a2 2 0 0 1 2 2v4a2 2 0 0 0 2 2 2 2 0 0 0-2 2v4a2 2 0 0 1-2 2h-1"/>',
  terminal: '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m6 9 3 3-3 3M12 15h6"/>',
  codigo: '<path d="m8 7-5 5 5 5M16 7l5 5-5 5M14 4l-4 16"/>',
  barras: '<path d="M4 20h16M6 16v-3M10 16V9M14 16v-5M18 16V5"/>',
  punto: '<circle cx="12" cy="12" r="3"/>',
};

// Índice por slug de lo que exporta el paquete (slug → {path, hex}).
const porSlug = {};
for (const v of Object.values(si)) {
  if (v && typeof v === 'object' && v.slug && v.path) porSlug[v.slug] = v;
}

// Colores muy oscuros o muy claros no se ven en uno de los dos temas:
// esos usan el color del texto (currentColor).
function luminancia(hex) {
  const c = [0, 2, 4].map((i) => parseInt(hex.slice(i, i + 2), 16) / 255)
    .map((x) => (x <= 0.03928 ? x / 12.92 : ((x + 0.055) / 1.055) ** 2.4));
  return 0.2126 * c[0] + 0.7152 * c[1] + 0.0722 * c[2];
}

const simbolos = [];
const filas = [];
const faltan = [];
const yaEnSprite = new Set();
for (const [nombre, slug] of Object.entries(SLUGS)) {
  if (!slug) continue;
  const i = porSlug[slug];
  if (!i) { faltan.push(`${nombre} (${slug})`); continue; }
  const id = 't-' + slug;
  if (!yaEnSprite.has(id)) {
    simbolos.push(`<symbol id="${id}" viewBox="0 0 24 24"><path d="${i.path}"/></symbol>`);
    yaEnSprite.add(id);
  }
  const L = luminancia(i.hex);
  const color = L < 0.03 || L > 0.8 ? 'currentColor' : '#' + i.hex;
  filas.push(`  '${nombre.replace(/'/g, "\\'")}': ['${color}', '${id}'],`);
}
for (const [n, d] of Object.entries(RELLENO)) simbolos.push(`<symbol id="c-${n}" viewBox="0 0 24 24"><path d="${d}"/></symbol>`);
// Los de trazo llevan su estilo dentro: el <svg> que los usa solo pone el color (currentColor).
for (const [n, s] of Object.entries(TRAZO)) {
  simbolos.push(`<symbol id="g-${n}" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${s}</g></symbol>`);
}

const svg = `<svg xmlns="http://www.w3.org/2000/svg">
<!-- Íconos del CV. ARCHIVO GENERADO por herramientas/iconos.mjs: no editar a mano.
     Logotipos de tecnologías: Simple Icons (CC0 1.0, https://simpleicons.org). -->
${simbolos.join('\n')}
</svg>
`;
writeFileSync(new URL('../public/assets/iconos.svg', import.meta.url), svg);

const ts = `// Índice del sprite public/assets/iconos.svg. ARCHIVO GENERADO por herramientas/iconos.mjs:
// no editar a mano.
// Tecnología => [color de relleno, id del símbolo en el sprite]
export const TECH_ICONS: Record<string, [string, string]> = {
${filas.join('\n')}
};

/** Íconos de contacto con relleno: símbolo «c-<nombre>». */
export const ICONOS_RELLENO: readonly string[] = ${JSON.stringify(Object.keys(RELLENO))};

/** Íconos de trazo (contacto, áreas de enfoque y genéricos): símbolo «g-<nombre>». */
export const ICONOS_TRAZO: readonly string[] = ${JSON.stringify(Object.keys(TRAZO))};
`;
writeFileSync(new URL('../lib/iconos.ts', import.meta.url), ts);

console.log(`public/assets/iconos.svg: ${simbolos.length} símbolos (${Math.round(svg.length / 1024)} KB)`);
console.log(`lib/iconos.ts: ${filas.length} tecnologías`);
if (faltan.length) console.log('No están en Simple Icons (se usará un ícono genérico):', faltan.join(', '));

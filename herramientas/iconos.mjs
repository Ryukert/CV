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
  'CSS': 'css',
  'JSON': 'json',
};

// Íconos de contacto con relleno (toman el color del texto). GitHub y ORCID salen de Simple Icons
// (ver más abajo); LinkedIn ya no está en Simple Icons y va aquí.
const RELLENO = {
  linkedin: 'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z',
};
const RELLENO_DE_SIMPLE_ICONS = { github: 'github', orcid: 'orcid' };

// Íconos de trazo propios, todos con el mismo estilo: caja de 24×24, trazo de 1.75, puntas y
// uniones redondeadas, margen de 2 px. Contacto, áreas de enfoque y un ícono por concepto para
// las tecnologías que no tienen logotipo.
const TRAZO = {
  // Contacto
  mail: '<rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="m3.5 7 8.5 6 8.5-6"/>',
  phone: '<path d="M21 16.4v2.8a1.9 1.9 0 0 1-2.1 1.9 18.8 18.8 0 0 1-8.2-2.9 18.5 18.5 0 0 1-5.7-5.7A18.8 18.8 0 0 1 2.1 4.2 1.9 1.9 0 0 1 4 2.2h2.8A1.9 1.9 0 0 1 8.7 3.8c.1.9.4 1.8.7 2.7a1.9 1.9 0 0 1-.4 2L7.8 9.7a15.2 15.2 0 0 0 5.7 5.7l1.2-1.2a1.9 1.9 0 0 1 2-.4c.9.3 1.8.6 2.7.7a1.9 1.9 0 0 1 1.6 1.9z"/>',
  // Áreas de enfoque
  chip: '<rect x="6" y="6" width="12" height="12" rx="2"/><rect x="9.5" y="9.5" width="5" height="5" rx="1"/><path d="M9.5 2.5V6M14.5 2.5V6M9.5 18v3.5M14.5 18v3.5M2.5 9.5H6M2.5 14.5H6M18 9.5h3.5M18 14.5h3.5"/>',
  onda: '<path d="M2 12h3l2.5-6 3.5 12 3.5-15 3 12 2-5 1 2H22"/>',
  codigo: '<path d="m8 7-5 5 5 5M16 7l5 5-5 5M13.5 4.5l-3 15"/>',
  red: '<circle cx="5" cy="7" r="2"/><circle cx="5" cy="17" r="2"/><circle cx="12" cy="5" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="12" cy="19" r="2"/><circle cx="19" cy="12" r="2"/><path d="m6.9 6.4 3.2-.8M6.8 8.1l3.4 2.8M6.8 15.9l3.4-2.8M6.9 17.6l3.2.8M14 5.9l3.4 4.6M14 12h3M14 18.1l3.4-4.6"/>',
  barras: '<path d="M3 21h18"/><path d="M6.5 17.5v-4M11 17.5V10M15.5 17.5v-6M20 17.5V6"/><path d="m4 10 5-4.5 4.5 3L20 3"/>',
  // Señales
  espectro: '<path d="M3 21h18"/><path d="M5 18v-2M8 18V8M11 18v-6M14 18V4M17 18v-8M20 18v-3"/>',
  curva: '<path d="M3 3v18h18"/><path d="M6.5 15c1.2-8 2.6-9 3.8-1.5.9 5 2.2 4.6 3.4.5 1-3.4 2.3-3.6 3.3-1.5.6 1.2 1.3 1.8 2.5 1.8"/>',
  filtro: '<path d="M3 3v18h18"/><path d="M6 8h6.5c2.7 0 3.5 9 7.5 9"/><path d="M13 5v14" stroke-dasharray="1.5 2.5" opacity=".6"/>',
  grafica: '<path d="M3 3v18h18"/><path d="m7 15 3.5-4.5 3 3L19 6"/><circle cx="19" cy="6" r="1.2"/>',
  // IA y datos
  seleccion: '<path d="M3.5 4.5h17l-6.5 7.5v6l-4 2.5V12z"/>',
  aproximado: '<path d="M4 9.5c2-2.2 4-2.2 6.2-.7 2.2 1.5 4.2 1.5 6.2-.7M4 15.5c2-2.2 4-2.2 6.2-.7 2.2 1.5 4.2 1.5 6.2-.7"/><circle cx="20" cy="8.6" r="1"/><circle cx="20" cy="14.6" r="1"/>',
  datos: '<ellipse cx="12" cy="5.5" rx="7.5" ry="2.8"/><path d="M4.5 5.5v13c0 1.6 3.4 2.8 7.5 2.8s7.5-1.2 7.5-2.8v-13M4.5 12c0 1.6 3.4 2.8 7.5 2.8s7.5-1.2 7.5-2.8"/>',
  // Hardware
  sensor: '<rect x="6" y="6" width="12" height="12" rx="2"/><path d="M9.5 2.5V6M14.5 2.5V6M9.5 18v3.5M14.5 18v3.5M2.5 9.5H6M2.5 14.5H6M18 9.5h3.5M18 14.5h3.5"/><path d="M9.5 14.5h5M9.5 14.5v-5M9.5 14.5l4-4"/>',
  bus: '<path d="M2.5 8.5h19M2.5 15.5h19"/><circle cx="6.5" cy="8.5" r="1.6"/><circle cx="17.5" cy="15.5" r="1.6"/><path d="M6.5 10.1v5.4M17.5 8.5v5.4M12 8.5v7"/>',
  // Web y sistemas
  poligono: '<path d="M5 7.5 12 3l7 4.5-1.5 9L12 21l-6-4.5z"/><circle cx="12" cy="3" r="1.3"/><circle cx="19" cy="7.5" r="1.3"/><circle cx="5" cy="7.5" r="1.3"/><circle cx="12" cy="21" r="1.3"/>',
  api: '<path d="M8 3.5H7a2 2 0 0 0-2 2v3.5a2 2 0 0 1-2 2v2a2 2 0 0 1 2 2v3.5a2 2 0 0 0 2 2h1M16 3.5h1a2 2 0 0 1 2 2v3.5a2 2 0 0 0 2 2v2a2 2 0 0 0-2 2v3.5a2 2 0 0 1-2 2h-1"/><path d="M9.5 12h5M12.5 10l2 2-2 2"/>',
  llave: '<circle cx="7.5" cy="16.5" r="4.5"/><path d="m10.7 13.3 9.8-9.8M16.5 7.5l3 3M14 10l2 2"/>',
  engranaje: '<circle cx="12" cy="12" r="3"/><path d="M12 2.5v3M12 18.5v3M2.5 12h3M18.5 12h3M5.3 5.3l2.1 2.1M16.6 16.6l2.1 2.1M5.3 18.7l2.1-2.1M16.6 7.4l2.1-2.1"/><circle cx="12" cy="12" r="6.5"/>',
  terminal: '<rect x="2.5" y="4" width="19" height="16" rx="2.5"/><path d="m6.5 9 3 3-3 3M12 15h5.5"/>',
  ventana: '<rect x="3" y="4" width="18" height="16" rx="2.5"/><path d="M3 9h18"/><path d="M6.5 6.5h.01M9 6.5h.01M11.5 6.5h.01" stroke-width="2.2"/>',
  // Para lo que no tenga ícono propio
  cubo: '<path d="m12 2.8 8.2 4.6v9.2L12 21.2l-8.2-4.6V7.4z"/><path d="m4 7.5 8 4.5 8-4.5M12 12v9"/>',
};

// Tecnologías sin logotipo → su ícono propio (como aparecen en datos/*.json, en los dos idiomas).
const GENERICO_POR_NOMBRE = {
  'FFT': 'espectro',
  'PSD/Welch': 'curva',
  'Filtros digitales': 'filtro', 'Digital filters': 'filtro',
  'Matplotlib': 'grafica', 'Recharts': 'grafica',
  'Machine Learning': 'red',
  'Selección de atributos': 'seleccion', 'Feature selection': 'seleccion',
  'Sensores MEMS': 'sensor', 'MEMS sensors': 'sensor',
  'I2C': 'bus', 'SPI': 'bus', 'UART': 'bus',
  'GeoJSON': 'poligono',
  'APIs REST': 'api', 'REST APIs': 'api',
  'SSH': 'llave',
  'systemd': 'engranaje',
  'CustomTkinter': 'ventana',
  'RapidFuzz': 'aproximado',
  'SQL': 'datos',
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
for (const [n, slug] of Object.entries(RELLENO_DE_SIMPLE_ICONS)) {
  if (!porSlug[slug]) throw new Error(`Simple Icons no tiene «${slug}»`);
  RELLENO[n] = porSlug[slug].path;
}
for (const [n, d] of Object.entries(RELLENO)) simbolos.push(`<symbol id="c-${n}" viewBox="0 0 24 24"><path d="${d}"/></symbol>`);
// Los de trazo llevan su estilo dentro: el <svg> que los usa solo pone el color (currentColor).
for (const [n, s] of Object.entries(TRAZO)) {
  simbolos.push(`<symbol id="g-${n}" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">${s}</g></symbol>`);
}
for (const [nombre, g] of Object.entries(GENERICO_POR_NOMBRE)) {
  if (!TRAZO[g]) throw new Error(`«${nombre}» apunta a un ícono que no existe: ${g}`);
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

/** Tecnologías sin logotipo => su ícono de trazo propio. */
export const ICONOS_GENERICOS: Record<string, string> = ${JSON.stringify(GENERICO_POR_NOMBRE, null, 2)};
`;
writeFileSync(new URL('../lib/iconos.ts', import.meta.url), ts);

console.log(`public/assets/iconos.svg: ${simbolos.length} símbolos (${Math.round(svg.length / 1024)} KB)`);
console.log(`lib/iconos.ts: ${filas.length} tecnologías`);
if (faltan.length) console.log('No están en Simple Icons (se usará un ícono genérico):', faltan.join(', '));

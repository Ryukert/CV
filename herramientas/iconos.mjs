// Genera lib/iconos.ts con los logotipos de las tecnologías que aparecen en el CV.
//
//   cd herramientas && npm i --no-save simple-icons@16.30.0 && node iconos.mjs
//
// Los trazos vienen de Simple Icons (CC0 1.0, https://simpleicons.org). Se copian al
// sitio para no depender de otro servidor: la CSP sigue siendo default-src 'self'.
// Para agregar una tecnología: añadirla a SLUGS (nombre tal como aparece en data.php →
// slug de Simple Icons) y volver a correr este script.
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

// Índice por slug de lo que exporta el paquete (siSlug → {path, hex}).
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

const filas = [];
const faltan = [];
for (const [nombre, slug] of Object.entries(SLUGS)) {
  if (!slug) continue;
  const i = porSlug[slug];
  if (!i) { faltan.push(`${nombre} (${slug})`); continue; }
  const L = luminancia(i.hex);
  const color = L < 0.03 || L > 0.8 ? 'currentColor' : '#' + i.hex;
  filas.push(`  '${nombre.replace(/'/g, "\\'")}': ['${color}', '${i.path}'],`);
}

const ts = `// Logotipos de tecnologías (Simple Icons, CC0 1.0). ARCHIVO GENERADO por
// herramientas/iconos.mjs: no editar a mano.
// nombre => [color de relleno, trazo SVG en una caja de 24×24]
export const TECH_ICONS: Record<string, [string, string]> = {
${filas.join('\n')}
};
`;
writeFileSync(new URL('../lib/iconos.ts', import.meta.url), ts);
console.log(`lib/iconos.ts: ${filas.length} íconos`);
if (faltan.length) console.log('No están en Simple Icons (se usará un ícono genérico):', faltan.join(', '));

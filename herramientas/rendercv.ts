// Genera el CV en PDF con RenderCV (https://rendercv.com) desde datos/*.json.
//
//   npm run pdf                    (= node herramientas/rendercv.ts --publicar)
//       Regenera los PDF sencillos que descarga el sitio (public/assets/cv-es.pdf y
//       cv-en.pdf) y su huella (public/assets/cv-pdf.json). Hay que correrlo cada vez que
//       cambian los datos: las pruebas fallan si el PDF quedó atrasado.
//
//   node herramientas/rendercv.ts es --version=acme-iot [--pdf]
//       Crea versiones/acme-iot/cv-es.yaml (todos los proyectos, sin asignaturas) para
//       adaptarlo a una vacante; con --pdf también genera el PDF.
//
// Necesita RenderCV: pip install "rendercv[full]". Si «python» no es el comando, usar la
// variable PYTHON. El YAML se escribe como JSON, que también es YAML válido.
import { spawnSync } from 'node:child_process';
import { copyFileSync, existsSync, mkdirSync, rmSync, statSync, writeFileSync } from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import { SITIO_PUBLICO, type Lang } from '../lib/cv-core.ts';
import { compartido, cvData } from '../lib/datos-node.ts';
import { cvRendercv, huella } from '../lib/rendercv-core.ts';

const RAIZ = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const args = process.argv.slice(2);
const sitio = (process.env.SITE_URL ?? '').replace(/\/+$/, '') || SITIO_PUBLICO;

/** Escribe el YAML y, si se pide, corre RenderCV. Devuelve la ruta del PDF o del YAML. */
function generar(cv: unknown, dir: string, nombre: string, pdf: boolean): string {
  mkdirSync(dir, { recursive: true });
  const yaml = path.join(dir, `${nombre}.yaml`);
  writeFileSync(yaml, '# Generado por herramientas/rendercv.ts desde datos/*.json.\n'
    + '# Se puede editar para adaptarlo a una vacante; volver a generarlo lo sobrescribe.\n'
    + JSON.stringify(cv, null, 4) + '\n');
  if (!pdf) return yaml;
  // RenderCV toma las rutas de salida relativas a la carpeta del YAML.
  const r = spawnSync(process.env.PYTHON || 'python', ['-m', 'rendercv', 'render', yaml, '--pdf-path', `${nombre}.pdf`,
    '--typst-path', `${nombre}.typ`, '--dont-generate-markdown', '--dont-generate-html', '--dont-generate-png', '--quiet'],
  { cwd: dir, stdio: 'inherit' });
  if (r.status !== 0 || !existsSync(path.join(dir, `${nombre}.pdf`))) {
    console.error(`RenderCV falló (código ${r.status}). ¿Está instalado? pip install "rendercv[full]"`);
    process.exit(1);
  }
  return path.join(dir, `${nombre}.pdf`);
}

if (args.includes('--publicar')) {
  const tmp = path.join(RAIZ, 'versiones', '.publicar');
  const huellas: Record<string, string> = {};
  for (const lang of ['es', 'en'] as Lang[]) {
    const d = cvData(lang);
    const pdf = generar(cvRendercv(d, true, sitio), tmp, `cv-${lang}`, true);
    const destino = path.join(RAIZ, 'public', 'assets', `cv-${lang}.pdf`);
    copyFileSync(pdf, destino);
    huellas[lang] = huella(d, sitio);
    console.log(`public/assets/cv-${lang}.pdf (${Math.round(statSync(destino).size / 1024)} KB)`);
  }
  writeFileSync(path.join(RAIZ, 'public', 'assets', 'cv-pdf.json'), JSON.stringify(
    { generado_con: 'npm run pdf', cv_updated: compartido.updated, sitio, huellas }, null, 4) + '\n');
  rmSync(tmp, { recursive: true, force: true });
  console.log('Huella: public/assets/cv-pdf.json');
} else {
  const lang: Lang = args.includes('en') ? 'en' : 'es';
  const version = args.map((a) => /^--version=([a-z0-9][a-z0-9-]{0,60})$/.exec(a)?.[1]).find(Boolean) ?? 'base';
  const salida = generar(cvRendercv(cvData(lang), false, sitio), path.join(RAIZ, 'versiones', version), `cv-${lang}`, args.includes('--pdf'));
  console.log((salida.endsWith('.pdf') ? 'PDF:  ' : 'YAML: ') + salida);
}

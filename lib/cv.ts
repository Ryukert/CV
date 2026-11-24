// Capa de datos de la web: une datos/*.json con lib/cv-core.ts.
import { headers } from 'next/headers';
import compartido from '@/datos/compartido.json';
import es from '@/datos/es.json';
import en from '@/datos/en.json';
import versiones from '@/lib/versiones.json';
import { armarCv, siteUrl, type Compartido, type Cv, type Idioma, type Lang } from '@/lib/cv-core.ts';

export function cvData(lang: Lang): Cv {
  // El JSON se valida al compilar contra los tipos de cv-core solo en forma; las tuplas
  // (project_text) TypeScript las ve como arreglos, de ahí el paso por unknown.
  return armarCv(compartido as Compartido, (lang === 'en' ? en : es) as unknown as Idioma);
}

export const CV_UPDATED = (compartido as Compartido).updated;

/** URL absoluta (canonical, Open Graph, sitemap…). Ver siteUrl en cv-core. */
export async function url(ruta = '/'): Promise<string> {
  const h = await headers();
  return siteUrl(ruta, h.get('host') ?? 'localhost', process.env);
}

const V = versiones as Record<string, { v: string; kb: number }>;

/** «/assets/style.css?v=1a2b3c4d»: la versión cambia cuando cambia el archivo. */
export function asset(archivo: string): string {
  return `/assets/${archivo}?v=${V[archivo]?.v ?? '0'}`;
}

/** Peso legible («81 KB»), o '' si el archivo no existe. */
export function assetSize(ruta: string): string {
  const kb = V[ruta.replace(/^\/assets\//, '')]?.kb;
  if (kb === undefined) return '';
  return kb >= 1024 ? `${(kb / 1024).toFixed(1)} MB` : `${kb} KB`;
}

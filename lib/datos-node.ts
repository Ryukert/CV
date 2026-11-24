// Lee el CV desde datos/*.json para las herramientas y pruebas que corren con Node
// directamente (sin Next.js). La web usa lib/cv.ts.
import { readFileSync } from 'node:fs';
import { armarCv, type Compartido, type Cv, type Idioma, type Lang } from './cv-core.ts';

const leer = (f: string) => JSON.parse(readFileSync(new URL(`../datos/${f}`, import.meta.url), 'utf8'));

export const compartido: Compartido = leer('compartido.json');

export function cvData(lang: Lang): Cv {
  return armarCv(compartido, leer(`${lang}.json`) as Idioma);
}

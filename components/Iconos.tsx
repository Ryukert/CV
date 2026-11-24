// Íconos del CV. Los trazos viven en el sprite public/assets/iconos.svg (lo genera
// herramientas/iconos.mjs); aquí cada ícono es solo <svg><use href="…#id"/></svg>, así el HTML
// no repite cientos de trazos y el navegador guarda el sprite un año.
import { asset } from '@/lib/cv.ts';
import { ICONOS_GENERICOS, ICONOS_RELLENO, TECH_ICONS } from '@/lib/iconos.ts';

/** <svg> que usa un símbolo del sprite. `fill` colorea los logotipos y los íconos con relleno. */
function Uso({ id, clase, fill }: { id: string; clase: string; fill?: string }) {
  return (
    <svg className={clase} viewBox="0 0 24 24" aria-hidden="true" fill={fill}>
      <use href={`${asset('iconos.svg')}#${id}`} />
    </svg>
  );
}

/** Ícono de contacto o de área de enfoque (toma el color del texto). */
export function Icono({ nombre }: { nombre: string }) {
  return ICONOS_RELLENO.includes(nombre)
    ? <Uso id={`c-${nombre}`} clase="ico" fill="currentColor" />
    : <Uso id={`g-${nombre}`} clase="ico" />;
}

// Respaldo para etiquetas nuevas que aún no están en ICONOS_GENERICOS (herramientas/iconos.mjs).
const GENERICOS: [RegExp, string][] = [
  [/FFT|espectro|spectrum/i, 'espectro'],
  [/PSD|Welch|RMS|señal|signal|series/i, 'onda'],
  [/filtr|filter/i, 'filtro'],
  [/plot|chart|gr[aá]fic/i, 'grafica'],
  [/learning|visi[oó]n|vision|neural|\bIA\b|\bAI\b/i, 'red'],
  [/MEMS|sensor|aceler|acceler/i, 'sensor'],
  [/I2C|SPI|UART|CAN\b|bus/i, 'bus'],
  [/GeoJSON|mapa|map/i, 'poligono'],
  [/SQL|base de datos|database/i, 'datos'],
  [/REST|API/i, 'api'],
  [/SSH|clave|key/i, 'llave'],
  [/systemd|servic/i, 'engranaje'],
  [/shell|terminal|CLI\b/i, 'terminal'],
];

/** Logotipo de una etiqueta de datos/*.json ([color, id]), o el nombre del ícono genérico. */
export function buscarLogo(etiqueta: string): [string, string] | string {
  // Candidatos: la etiqueta sin paréntesis, cada parte separada por « / » y lo de los paréntesis.
  const parentesis = [...etiqueta.matchAll(/\(([^)]*)\)/g)].map((m) => m[1]);
  const base = etiqueta.replace(/\s*\([^)]*\)\s*/g, ' ').trim();
  const partes = [base, ...base.split(/\s+\/\s+/), ...parentesis];

  for (const p of partes) if (TECH_ICONS[p]) return TECH_ICONS[p];
  // Prefijo más largo: «Vercel Functions» → Vercel, «Arduino Nano RP2040» → Arduino.
  let mejor = '';
  for (const p of partes) {
    for (const k of Object.keys(TECH_ICONS)) {
      if (p.startsWith(k + ' ') && k.length > mejor.length) mejor = k;
    }
  }
  if (mejor) return TECH_ICONS[mejor];
  for (const p of partes) if (ICONOS_GENERICOS[p]) return ICONOS_GENERICOS[p];
  return GENERICOS.find(([re]) => re.test(etiqueta))?.[1] ?? 'cubo';
}

/** Ícono de una tecnología («React 18», «Linux / Ubuntu Server», «Pruebas en navegador (Puppeteer)»…). */
export function IconoTecnologia({ etiqueta }: { etiqueta: string }) {
  const logo = buscarLogo(etiqueta);
  if (typeof logo === 'string') return <Uso id={`g-${logo}`} clase="tico generico" />;
  return <Uso id={logo[1]} clase="tico" fill={logo[0]} />;
}

// Íconos SVG en línea. No son scripts ni estilos: la CSP estricta no les afecta.
import { TECH_ICONS } from '@/lib/iconos.ts';

const RELLENO: Record<string, string> = {
  github: 'M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12',
  linkedin: 'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z',
};

// Íconos de trazo (caja de 24×24). El contenido es fijo, escrito aquí: no hay datos externos.
const TRAZO: Record<string, string> = {
  mail: '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/>',
  phone: '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
  orcid: '<circle cx="12" cy="12" r="10.5"/><path d="M8.5 10v7M8.5 7v.01M12 10v7h2.2a3.5 3.5 0 0 0 0-7H12z"/>',
  // Genéricos para tecnologías sin logotipo
  onda: '<path d="M2 12h3l3-8 4 16 4-12 2 4h4"/>',
  red: '<circle cx="5" cy="6" r="2"/><circle cx="5" cy="18" r="2"/><circle cx="19" cy="12" r="2"/><path d="m7 6.8 10 4.4M7 17.2l10-4.4"/>',
  chip: '<rect x="6" y="6" width="12" height="12" rx="1.5"/><path d="M9 2v4M15 2v4M9 18v4M15 18v4M2 9h4M2 15h4M18 9h4M18 15h4"/>',
  mapa: '<path d="M9 4 3 6v14l6-2 6 2 6-2V4l-6 2-6-2zM9 4v14M15 6v14"/>',
  datos: '<ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 5v14c0 1.7 3.6 3 8 3s8-1.3 8-3V5M4 12c0 1.7 3.6 3 8 3s8-1.3 8-3"/>',
  api: '<path d="M8 3H7a2 2 0 0 0-2 2v4a2 2 0 0 1-2 2 2 2 0 0 1 2 2v4a2 2 0 0 0 2 2h1M16 3h1a2 2 0 0 1 2 2v4a2 2 0 0 0 2 2 2 2 0 0 0-2 2v4a2 2 0 0 1-2 2h-1"/>',
  terminal: '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m6 9 3 3-3 3M12 15h6"/>',
  punto: '<circle cx="12" cy="12" r="3"/>',
};

function Trazo({ nombre, clase }: { nombre: string; clase: string }) {
  return (
    <svg className={clase} viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" strokeWidth="2"
      strokeLinecap="round" strokeLinejoin="round" dangerouslySetInnerHTML={{ __html: TRAZO[nombre] ?? '' }} />
  );
}

/** Ícono de la barra de contacto (hereda el color del texto). */
export function Icono({ nombre }: { nombre: string }) {
  if (RELLENO[nombre]) {
    return <svg className="ico" viewBox="0 0 24 24" aria-hidden="true" fill="currentColor"><path d={RELLENO[nombre]} /></svg>;
  }
  return <Trazo nombre={nombre} clase="ico" />;
}

const GENERICOS: [RegExp, string][] = [
  [/FFT|PSD|Welch|RMS|filtr|filter|series|Matplotlib|señal|signal/i, 'onda'],
  [/learning|visi[oó]n|vision|big data|atributo|feature|\bIA\b|\bAI\b/i, 'red'],
  [/MEMS|I2C|SPI|UART|sensor/i, 'chip'],
  [/GeoJSON|mapa|map/i, 'mapa'],
  [/SQL|base de datos|database/i, 'datos'],
  [/REST|API/i, 'api'],
  [/SSH|systemd|shell/i, 'terminal'],
];

/** Logotipo de la tecnología de una etiqueta de data.php, o la clave del genérico. */
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
  return GENERICOS.find(([re]) => re.test(etiqueta))?.[1] ?? 'punto';
}

/** Ícono de una tecnología («React 18», «Linux / Ubuntu Server», «Pruebas en navegador (Puppeteer)»…). */
export function IconoTecnologia({ etiqueta }: { etiqueta: string }) {
  const logo = buscarLogo(etiqueta);
  if (typeof logo === 'string') return <Trazo nombre={logo} clase="tico generico" />;
  return <svg className="tico" viewBox="0 0 24 24" aria-hidden="true" fill={logo[0]}><path d={logo[1]} /></svg>;
}

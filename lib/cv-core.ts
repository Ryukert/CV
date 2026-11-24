// Arma el CV de un idioma a partir de los datos de datos/*.json.
//
// Es TypeScript sin dependencias ni importaciones: lo usan la web (lib/cv.ts), el PDF
// (herramientas/rendercv.ts) y las pruebas (tests/humo.ts), estas dos con Node directamente.

export type Lang = 'es' | 'en';

export interface Profile {
  name: string; email: string; phone: string; github: string; linkedin: string; orcid: string;
}

export interface Project {
  key: string;
  stack: string;          // «Python · FastAPI»
  url: string | null;
  date: string;           // mes de inicio, AAAA-MM
  ongoing?: boolean;
  featured?: boolean;
  pdf?: boolean;          // aparece en el PDF sencillo
}

export interface Publication {
  authors: string; year: number; venue: string; url: string;
  title: Record<Lang, string>; role: Record<Lang, string>;
}

export interface Compartido {
  updated: string;        // AAAA-MM-DD, fecha de la última actualización del contenido
  profile: Profile;
  publications: Publication[];
  projects: Project[];
  experience_tech: Record<string, string[]>;
  metrics: { value: string; key: string }[];
}

export interface Grupo { name: string; items: string[] }

export interface Idioma {
  lang: Lang; locale: string; role: string; subrole: string; location: string; available: string; meta_desc: string;
  nav: Record<string, string>;
  cta_pdf: string; cta_design: string; switch: string; switch_url: string;
  summary: string;
  metric_labels: Record<string, string>;
  sections: Record<string, string>;
  achievements: { title: string; text: string }[];
  experience: { period: string; org: string; title: string; tech?: string; intro: string; groups: Grupo[] }[];
  talk: { label: string; text: string };
  project_text: Record<string, [string, string]>;
  areas: {
    toggle: string; intro: string; legend: { applied: string; coursework: string };
    items: { name: string; applied: boolean; evidence?: string; courses: string[] }[];
  };
  education: {
    period: string; org: string; title: string; note: string; stats?: string[];
    coursework?: { label: string; note?: string; groups: Grupo[] };
  }[];
  other: { period: string; org: string; title: string; note: string }[];
  skills: Record<string, string[]>;
  certs: string[];
  languages: string[];
  contact_intro: string; footer: string; updated_label: string; updated: string;
  ui: Record<string, unknown>;
}

export type Cv = Idioma & {
  profile: Profile;
  publications: Publication[];
  projects: Project[];
  experience_tech: Record<string, string[]>;
  metrics: { value: string; key: string }[];
  pdf: { plain: string; design: string };
  updated_iso: string;
};

/** Destacado primero; después del más reciente al más antiguo (sort es estable). */
export function ordenarProyectos(p: Project[]): Project[] {
  return [...p].sort((a, b) =>
    Number(!!b.featured) - Number(!!a.featured) || b.date.localeCompare(a.date));
}

export function armarCv(c: Compartido, idioma: Idioma): Cv {
  const en = idioma.lang === 'en';
  return {
    ...idioma,
    profile: c.profile,
    publications: c.publications,
    projects: ordenarProyectos(c.projects),
    experience_tech: c.experience_tech,
    metrics: c.metrics,
    pdf: en
      ? { plain: '/assets/cv-en.pdf', design: '/assets/cv-en-design.pdf' }
      : { plain: '/assets/cv-es.pdf', design: '/assets/cv-es-diseno.pdf' },
    updated_iso: c.updated,
  };
}

/** «2026-11» → «nov 2026» (es) o «Nov 2026» (en). */
export function mesAnio(ym: string, lang: Lang): string {
  const [y, m] = ym.split('-').map(Number);
  const meses = lang === 'en'
    ? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    : ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
  return `${meses[m - 1]} ${y}`;
}

/**
 * Dirección pública del sitio. Nunca sale del encabezado Host en producción: un Host
 * falso podría quedar guardado en la caché y repartirse a otros visitantes.
 *   1. SITE_URL, si existe y es válida (dominio propio).
 *   2. En Vercel, SITIO_PUBLICO.
 *   3. En desarrollo, el host de la petición (http solo en localhost).
 */
export const SITIO_PUBLICO = 'https://cv-coral-pi.vercel.app';

export function siteUrl(ruta = '/', host = 'localhost', env: Record<string, string | undefined> = {}): string {
  let base = (env.SITE_URL ?? '').replace(/\/+$/, '');
  if (!/^https?:\/\/[A-Za-z0-9.-]+(:\d+)?$/.test(base)) {
    if (env.VERCEL) {
      base = SITIO_PUBLICO;
    } else {
      const h = host.replace(/[^A-Za-z0-9.\-:[\]]/g, '');
      const local = /^(localhost|127\.0\.0\.1|\[::1\])(:\d+)?$/.test(h);
      base = (local ? 'http' : 'https') + '://' + h;
    }
  }
  return base + '/' + ruta.replace(/^\/+/, '');
}

// Convierte el CV al formato de RenderCV (https://rendercv.com).
// Lo usan herramientas/rendercv.ts (genera los PDF) y tests/humo.ts (comprueba que los PDF
// publicados corresponden a los datos actuales).
//
// completo = true es el PDF sencillo que descarga el sitio: logros y los proyectos marcados
// con "pdf": true. false es la base para adaptar a una vacante (todos los proyectos, sin logros).
import { createHash } from 'node:crypto';
import type { Cv } from './cv-core.ts';

/** Escapa los caracteres que RenderCV interpreta como Markdown en textos que no lo son. */
export function rcvMd(s: string): string {
  return s.replace(/[*_[\]]/g, (c) => '\\' + c);
}

const ultimo = (u: string) => u.replace(/\/+$/, '').split('/').pop() ?? '';

export function cvRendercv(d: Cv, completo = true, sitio = ''): Record<string, unknown> {
  const en = d.lang === 'en';
  const t = en
    ? { summary: 'Profile', certs: 'Certifications', langs: 'Languages', language: 'english' }
    : { summary: 'Perfil profesional', certs: 'Certificaciones', langs: 'Idiomas', language: 'spanish' };
  const sec = d.sections;

  const logros = d.achievements.map((a) => ({ bullet: `**${rcvMd(a.title)}** ${rcvMd(a.text)}` }));

  const experiencia = d.experience.map((job) => ({
    company: rcvMd(job.org), position: rcvMd(job.title), date: job.period,
    summary: rcvMd(job.intro), highlights: job.groups.flatMap((g) => g.items.map(rcvMd)),
  }));

  const proyectos = d.projects
    .filter((p) => !completo || p.pdf)
    .map((p) => {
      const [titulo, desc] = d.project_text[p.key];
      return {
        name: p.url ? `[${rcvMd(titulo)}](${p.url})` : rcvMd(titulo),
        summary: rcvMd(desc),
        highlights: [rcvMd(p.stack)],
      };
    });

  const pubs = d.publications.map((p) => ({
    // «Apellido, I. N., Apellido, I.» → un autor por elemento; el propio en negritas.
    title: `${rcvMd(p.title[d.lang])} (${rcvMd(p.role[d.lang])})`,
    authors: p.authors.split(/(?<=\.),\s+(?=[A-ZÁÉÍÓÚÑ])/u).map((a) => (a.startsWith('Adame Rodríguez') ? `**${a}**` : a)),
    // El año va con la revista: como fecha, RenderCV lo mostraría como «Ene 2026».
    journal: `${p.venue}, ${p.year}`,
    url: p.url,
  }));
  const charla = [`**${rcvMd(d.talk.label)}:** ${rcvMd(d.talk.text)}`];

  const formacion = d.education.map((ed) => ({
    institution: ed.org, area: rcvMd(ed.title), date: ed.period,
    highlights: [rcvMd((ed.stats ?? []).join(' · ')), rcvMd(ed.note)],
  }));

  const otra = d.other.map((o) => ({ company: rcvMd(o.org), position: rcvMd(o.title), date: o.period, summary: rcvMd(o.note) }));
  const competencias = Object.entries(d.skills).map(([g, items]) => ({ label: rcvMd(g), details: rcvMd(items.join(', ')) }));

  const secciones: Record<string, unknown[]> = { [t.summary]: [rcvMd(d.summary)] };
  if (completo) secciones[sec.logros] = logros;
  Object.assign(secciones, {
    [sec.exp]: experiencia,
    [sec.skills]: competencias,
    [sec.projects]: proyectos,
    [sec.pubs]: pubs,
    [d.talk.label]: charla,
    [sec.edu]: formacion,
    [sec.other]: otra,
    [t.certs]: d.certs.map((c) => ({ bullet: rcvMd(c) })),
    [t.langs]: d.languages.map((l) => ({ bullet: rcvMd(l) })),
  });

  const cv: Record<string, unknown> = {
    name: d.profile.name,
    headline: `${d.role} · ${d.subrole}`,
    location: d.location,
    email: d.profile.email,
    phone: d.profile.phone.replace(/\s+/g, ''),
    social_networks: [
      { network: 'LinkedIn', username: decodeURIComponent(ultimo(d.profile.linkedin)) },
      { network: 'GitHub', username: ultimo(d.profile.github) },
      { network: 'ORCID', username: ultimo(d.profile.orcid) },
    ],
    sections: secciones,
  };
  if (sitio) cv.website = sitio.replace(/\/+$/, '') + (en ? '/en' : '/');

  return {
    cv,
    // Teléfono con +52: el CV se manda a vacantes remotas fuera de México. Compacto para que
    // quepa en pocas páginas; las entradas largas se pueden partir entre páginas.
    design: {
      theme: 'classic',
      page: { size: 'us-letter', top_margin: '0.5in', bottom_margin: '0.5in', left_margin: '0.55in', right_margin: '0.55in' },
      typography: { font_size: { body: '9.5pt', name: '24pt' }, line_spacing: '0.5em' },
      header: { space_below_name: '0.4cm', space_below_headline: '0.4cm', space_below_connections: '0.4cm',
        connections: { phone_number_format: 'international' } },
      section_titles: { space_above: '0.35cm', space_below: '0.2cm' },
      sections: { space_between_regular_entries: '0.7em' },
      entries: { allow_page_break: true },
    },
    locale: { language: t.language },
    // Sin «Última actualización» con la fecha del día: los mismos datos dan el mismo PDF.
    settings: { current_date: d.updated_iso },
  };
}

/** Huella de lo que se mandó a RenderCV; si cambian los datos, cambia la huella. */
export function huella(d: Cv, sitio: string): string {
  return createHash('sha256').update(JSON.stringify(cvRendercv(d, true, sitio))).digest('hex');
}

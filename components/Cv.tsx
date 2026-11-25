// El CV completo de un idioma. Componente de servidor: no manda React al navegador;
// las funciones interactivas las pone public/assets/cv.js (mejora progresiva).
import { asset, assetSize, cvData, url, VERSION } from '@/lib/cv.ts';
import { mesAnio, type Lang } from '@/lib/cv-core.ts';
import { Icono, IconoTecnologia } from '@/components/Iconos.tsx';

/** JSON dentro de <script>: sin «<», «>» ni «&» que pudieran cerrar la etiqueta. */
function jsonSeguro(v: unknown): string {
  return JSON.stringify(v).replace(/</g, '\\u003c').replace(/>/g, '\\u003e').replace(/&/g, '\\u0026');
}

const telHref = (t: string) => 'tel:' + t.replace(/[^0-9+]/g, '');
const repoCorto = (u: string) => {
  const p = new URL(u);
  const ruta = p.pathname.replace(/^\/+|\/+$/g, '');
  return ruta ? 'github.com/' + ruta : p.hostname.replace(/^www\./, '');
};

export default async function Cv({ lang }: { lang: Lang }) {
  const d = cvData(lang);
  const en = lang === 'en';
  const otro = en ? 'es' : 'en';
  const aqui = await url(en ? '/en' : '/');
  const titulo = `${d.profile.name} — ${d.role}`;
  const og = await url(asset('og.jpg'));
  const mon = d.ui.monitor as Record<string, string>;

  const ld = {
    '@context': 'https://schema.org',
    '@type': 'Person',
    name: d.profile.name,
    jobTitle: d.role,
    description: d.meta_desc,
    email: 'mailto:' + d.profile.email,
    url: aqui,
    sameAs: [d.profile.github, d.profile.linkedin, d.profile.orcid],
    affiliation: { '@type': 'CollegeOrUniversity', name: 'Universidad Autónoma de Guerrero' },
    alumniOf: { '@type': 'CollegeOrUniversity', name: 'Universidad Autónoma de Guerrero' },
    knowsAbout: [...new Set(Object.values(d.skills).flat())],
    hasCredential: d.education.map((ed) => ({
      '@type': 'EducationalOccupationalCredential',
      name: ed.title,
      credentialCategory: 'degree',
      recognizedBy: { '@type': 'CollegeOrUniversity', name: ed.org },
    })),
    knowsLanguage: ['es', 'en'],
  };

  return (
    <>
      {/* React 19 sube <title>, <meta> y <link> al <head>. */}
      <title>{titulo}</title>
      <meta name="description" content={d.meta_desc} />
      <meta name="robots" content="index, follow" />
      <meta property="og:title" content={titulo} />
      <meta property="og:description" content={d.meta_desc} />
      <meta property="og:type" content="profile" />
      <meta property="og:locale" content={d.locale} />
      <meta property="og:locale:alternate" content={en ? 'es_MX' : 'en_US'} />
      <meta property="og:url" content={aqui} />
      <meta property="og:image" content={og} />
      <meta property="og:image:width" content="1200" />
      <meta property="og:image:height" content="630" />
      <meta property="og:image:alt" content={titulo} />
      <meta name="twitter:card" content="summary_large_image" />
      <meta name="twitter:title" content={titulo} />
      <meta name="twitter:description" content={d.meta_desc} />
      <meta name="twitter:image" content={og} />
      <link rel="canonical" href={aqui} />
      <link rel="alternate" hrefLang="es" href={await url('/')} />
      <link rel="alternate" hrefLang="en" href={await url('/en')} />
      <link rel="alternate" hrefLang="x-default" href={await url('/')} />

      {/* Bloques de datos: no se ejecutan, así que la CSP no los afecta. */}
      <script type="application/json" id="cv-textos"
        dangerouslySetInnerHTML={{ __html: jsonSeguro({ ...d.ui, url: aqui, titulo }) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: jsonSeguro(ld) }} />

      <a className="skip" href="#main">{en ? 'Skip to content' : 'Ir al contenido'}</a>

      <header className="hero">
        <div className="wrap hero-grid">
          <div>
            <h1>{d.profile.name}</h1>
            <p className="role">{d.role}</p>
            <p className="subrole">{d.subrole}</p>
            <p className="meta">{d.location} · {d.available}</p>
            <p className="links">
              <a href={'mailto:' + d.profile.email}><Icono nombre="mail" />{d.profile.email}</a>
              <button type="button" className="copiar" data-copiar={d.profile.email} aria-label={String(d.ui.copiar_correo)} hidden>{String(d.ui.copiar)}</button>
              <a href={telHref(d.profile.phone)}><Icono nombre="phone" />{d.profile.phone}</a>
              <a href={d.profile.github} rel="me noopener"><Icono nombre="github" />GitHub</a>
              <a href={d.profile.linkedin} rel="me noopener"><Icono nombre="linkedin" />LinkedIn</a>
              <a href={d.profile.orcid} rel="me noopener"><Icono nombre="orcid" />ORCID</a>
            </p>
            <p className="actions">
              <a className="btn" href={asset(d.pdf.plain.split('/').pop()!)} download>
                {d.cta_pdf} <span className="btn-meta">{assetSize(d.pdf.plain)}</span>
              </a>
              <a className="btn ghost" href={asset(d.pdf.design.split('/').pop()!)} download>
                {d.cta_design} <span className="btn-meta">PDF · {assetSize(d.pdf.design)}</span>
              </a>
              <a className="btn ghost" href={d.switch_url} lang={otro} hrefLang={otro}>{d.switch}</a>
              <button type="button" className="btn ghost" id="compartir" hidden>{String(d.ui.compartir)}</button>
            </p>
          </div>
          <div className="hero-lateral">
            {/* Monitor de señal: oculto sin JavaScript; public/assets/monitor.js lo enciende. */}
            <figure className="monitor" id="monitor" hidden aria-labelledby="monitor-titulo">
              <figcaption className="monitor-cab">
                <span className="led" aria-hidden="true"></span>
                <span id="monitor-titulo">{mon.titulo}</span>
                <button type="button" className="monitor-btn" id="monitor-pausa" aria-pressed="false">{mon.pausa}</button>
              </figcaption>
              <canvas className="monitor-onda" id="monitor-onda" aria-hidden="true"></canvas>
              <canvas className="monitor-fft" id="monitor-fft" aria-hidden="true"></canvas>
              <dl className="monitor-datos">
                <div><dt>f₀</dt><dd id="monitor-f0">—</dd></div>
                <div><dt>RMS</dt><dd id="monitor-rms">—</dd></div>
                <div><dt>fs</dt><dd>200 Hz</dd></div>
                <div><dt>{en ? 'state' : 'estado'}</dt><dd id="monitor-estado">{mon.reposo}</dd></div>
              </dl>
              <p className="monitor-nota">{mon.nota}</p>
            </figure>
            <aside className="enfoque" aria-labelledby="enfoque-titulo">
              <p className="enfoque-titulo" id="enfoque-titulo">{d.sections.focus}</p>
              <ul>
                {d.focus.map((f) => <li key={f.label}><Icono nombre={f.icon} />{f.label}</li>)}
              </ul>
            </aside>
          </div>
        </div>
      </header>

      <nav className="nav" aria-label={en ? 'Sections' : 'Secciones'}>
        <div className="wrap nav-bar">
          <div className="nav-inner">
            {Object.entries(d.nav).map(([id, label]) => <a key={id} href={'#' + id}>{label}</a>)}
          </div>
          <button type="button" className="tema" id="tema" hidden aria-label={String(d.ui.tema_oscuro)} title={String(d.ui.tema_oscuro)}>
            <svg className="sol" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><circle cx="12" cy="12" r="4.5" /><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4" /></svg>
            <svg className="luna" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z" /></svg>
          </button>
        </div>
      </nav>

      <main className="wrap" id="main">
        <section className="summary" id="about">
          <h2>{d.sections.about}</h2>
          <div className="about-grid">
            <p>{d.summary}</p>
            <aside className="ficha">
              <h3>{d.sections.langs}</h3>
              <ul className="idiomas">
                {d.languages.map((l) => {
                  const [idioma, ...resto] = l.split(':');
                  return <li key={l}><strong>{idioma.trim()}</strong> {resto.join(':').trim()}</li>;
                })}
              </ul>
            </aside>
          </div>
        </section>

        <section id="logros">
          <h2>{d.sections.logros}</h2>
          <ul className="logros">
            {d.achievements.map((a) => <li key={a.title}><strong>{a.title}</strong> {a.text}</li>)}
          </ul>
        </section>

        <div className="buscador" id="buscador" role="search" hidden>
          <label htmlFor="q">{String(d.ui.buscar)} <span className="atajo">{mon.atajo}</span></label>
          <div className="buscador-fila">
            <input type="search" id="q" placeholder={String(d.ui.buscar_ph)} autoComplete="off" spellCheck={false} aria-describedby="q-estado q-ayuda" />
            <button type="button" className="limpiar" id="q-limpiar" hidden>{String(d.ui.limpiar)}</button>
          </div>
          <p className="buscador-estado" id="q-estado" role="status" aria-live="polite"></p>
          <p className="buscador-ayuda" id="q-ayuda">{String(d.ui.buscar_ayuda)}</p>
        </div>

        <section id="exp">
          <h2>{d.sections.exp}</h2>
          {d.experience.map((job) => (
            <article className="entry" key={job.title}>
              <div className="entry-head">
                <h3>{job.title}</h3>
                <span className="period">{job.period}</span>
              </div>
              <p className="org">{job.org}</p>
              <p className="intro">{job.intro}</p>
              {job.tech && (
                <ul className="tech" aria-label={d.sections.stack}>
                  {d.experience_tech[job.tech].map((t) => <li key={t}><IconoTecnologia etiqueta={t} />{t}</li>)}
                </ul>
              )}
              <div className="grupos">
                {job.groups.map((g) => (
                  <div className="grupo" key={g.name}>
                    <h4>{g.name}</h4>
                    <ul>{g.items.map((it) => <li key={it}>{it}</li>)}</ul>
                  </div>
                ))}
              </div>
            </article>
          ))}
        </section>

        <section id="projects">
          <h2>{d.sections.projects}</h2>
          <div className="cards">
            {d.projects.map((pr) => {
              const [title, desc] = d.project_text[pr.key];
              return (
                <article key={pr.key} className={'card' + (pr.featured ? ' featured' : '') + (pr.url ? ' has-link' : '')}>
                  <p className="fecha">
                    <time dateTime={pr.date}>{mesAnio(pr.date, lang)}</time>{pr.ongoing ? ' – ' + d.sections.present : ''}
                  </p>
                  <h3>{title}</h3>
                  <p>{desc}</p>
                  <ul className="tech stack" aria-label={d.sections.stack}>
                    {pr.stack.split(' · ').map((t) => <li key={t}><IconoTecnologia etiqueta={t} />{t}</li>)}
                  </ul>
                  {pr.url && <a className="repo" href={pr.url} rel="noopener">{repoCorto(pr.url)}</a>}
                </article>
              );
            })}
          </div>
        </section>

        <section id="skills">
          <h2>{d.sections.skills}</h2>
          <div className="skills-grid">
            {Object.entries(d.skills).map(([grupo, items]) => (
              <div className="skillrow" key={grupo}>
                <h4>{grupo}</h4>
                <ul className="chips iconos">
                  {items.map((i) => <li key={i}><IconoTecnologia etiqueta={i} />{i}</li>)}
                </ul>
              </div>
            ))}
          </div>
        </section>

        <section id="pubs">
          <h2>{d.sections.pubs}</h2>
          <ol className="pubs">
            {d.publications.map((p) => (
              <li key={p.url}>
                <a href={p.url} rel="noopener">{p.title[lang]}</a>
                <p className="cite">{p.authors} ({p.year}). <em>{p.venue}</em>.{' '}
                  <span className="tag">{p.role[lang]}</span>
                </p>
              </li>
            ))}
          </ol>
          <p className="talk"><strong>{d.talk.label}:</strong> {d.talk.text}</p>
        </section>

        <section id="edu">
          <h2>{d.sections.edu}</h2>
          <div className="edu-grid">
          <div>
          {d.education.map((ed) => (
            <article className="entry compact" key={ed.title}>
              <div className="entry-head">
                <h3>{ed.title}</h3>
                <span className="period">{ed.period}</span>
              </div>
              <p className="org">{ed.org}</p>
              {ed.stats && ed.stats.length > 0 && (
                <ul className="chips stats">{ed.stats.map((s) => <li key={s}>{s}</li>)}</ul>
              )}
              <p className="note">{ed.note}</p>
            </article>
          ))}
          </div>

          <div>
          <h3 className="sub">{d.sections.other}</h3>
          {d.other.map((o) => (
            <article className="entry compact" key={o.title}>
              <div className="entry-head">
                <h3>{o.title}</h3>
                <span className="period">{o.period}</span>
              </div>
              <p className="org">{o.org}</p>
              <p>{o.note}</p>
            </article>
          ))}

          <div className="two-col">
            <div>
              <h4>{en ? 'Certifications' : 'Certificaciones'}</h4>
              <ul>{d.certs.map((c) => <li key={c}>{c}</li>)}</ul>
            </div>
          </div>
          </div>
          </div>
        </section>

        <section id="contact" className="contact">
          <h2>{d.sections.contact}</h2>
          <p>{d.contact_intro}</p>
          <p className="big">
            <a href={'mailto:' + d.profile.email}>{d.profile.email}</a>
            <button type="button" className="copiar" data-copiar={d.profile.email} aria-label={String(d.ui.copiar_correo)} hidden>{String(d.ui.copiar_correo)}</button>
          </p>
          <p className="meta">{d.profile.phone} · {d.location}</p>
        </section>
      </main>

      <footer>
        <div className="wrap">
          <nav className="pie-nav" aria-label={en ? 'Sections (footer)' : 'Secciones (pie)'}>
            {Object.entries(d.nav).map(([id, label]) => <a key={id} href={'#' + id}>{label}</a>)}
          </nav>
          <p className="pie-redes" aria-label={d.sections.social}>
            <a href={d.profile.github} rel="noopener" aria-label="GitHub" title="GitHub"><Icono nombre="github" /></a>
            <a href={d.profile.linkedin} rel="noopener" aria-label="LinkedIn" title="LinkedIn"><Icono nombre="linkedin" /></a>
            <a href={d.profile.orcid} rel="noopener" aria-label="ORCID" title="ORCID"><Icono nombre="orcid" /></a>
            <a href={'mailto:' + d.profile.email} aria-label={en ? 'Email' : 'Correo'} title={en ? 'Email' : 'Correo'}><Icono nombre="mail" /></a>
          </p>
          <p>{d.footer}</p>
          <p>{d.updated_label}: <time dateTime={d.updated_iso}>{d.updated}</time> · <a href={'/api/cv.json?lang=' + lang}>/api/cv.json</a>
            {' · '}<a className="version" href={`https://github.com/Ryukert/CV/blob/v${VERSION}/CHANGELOG.md`} rel="noopener"
              title={en ? 'Changelog' : 'Registro de cambios'}>{`v${VERSION}`}</a></p>
        </div>
      </footer>

      <div className="aviso" id="aviso" role="status" aria-live="polite"></div>
    </>
  );
}

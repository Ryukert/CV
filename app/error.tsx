'use client';
// Si una página falla al generarse, se muestra esto en lugar de la pantalla genérica de
// Next.js. No enseña el mensaje ni la pila del error (eso queda en los registros de Vercel);
// solo el identificador («digest») para poder buscarlo ahí.
export default function ErrorDePagina({ error, reset }: { error: Error & { digest?: string }; reset: () => void }) {
  return (
    <>
      <title>Error — CV</title>
      <meta name="robots" content="noindex" />
      <main className="wrap notfound">
        <h1>Error</h1>
        <p>No se pudo mostrar esta página. / <span lang="en">This page could not be displayed.</span></p>
        {error.digest && <p className="meta">Ref: <code>{error.digest}</code></p>}
        <p className="actions">
          <button type="button" className="btn" onClick={reset}>Reintentar · <span lang="en">Retry</span></button>
          <a className="btn ghost" href="/assets/cv-es.pdf">CV en PDF</a>
          <a className="btn ghost" href="/assets/cv-en.pdf" lang="en" hrefLang="en">CV (PDF, EN)</a>
        </p>
      </main>
    </>
  );
}

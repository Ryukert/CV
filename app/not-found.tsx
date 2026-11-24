export default function NoEncontrada() {
  return (
    <>
      <title>404 — Página no encontrada</title>
      <meta name="robots" content="noindex" />
      <main className="wrap notfound">
        <h1>404</h1>
        <p>Esta página no existe. / <span lang="en">This page does not exist.</span></p>
        <p className="actions">
          <a className="btn" href="/">Inicio</a>
          <a className="btn ghost" href="/en" lang="en" hrefLang="en">Home (EN)</a>
        </p>
      </main>
    </>
  );
}

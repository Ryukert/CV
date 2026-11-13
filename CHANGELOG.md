# Cambios

## [Sin publicar]

### Contenido
- Dos proyectos nuevos: **Ecosistema de riesgo sísmico de Tijuana** e **Infraestructura Verde · Baja
  California**, con enlace a sus sitios publicados (los repositorios son privados).
- Pasan a la web datos que solo estaban en el PDF: TOEFL ITP 470 (2025), las herramientas de
  procesamiento de archivos TXT/CSV/JSON/JSONL y Firebase.
- Competencias: grupo «Web y mapas» (Leaflet, GeoJSON, OpenStreetMap, PWA, pruebas en navegador),
  Node.js, Preact, Vercel Functions y GitHub Actions.
- El resumen menciona el desarrollo web geoespacial; el pie dice la fecha de la última actualización.

### Rendimiento y accesibilidad
- Fuente IBM Plex Sans servida por el sitio (antes, Google Fonts): sin esperar a otro servidor.
- Foto en WebP de 150 y 300 px (5 KB en lugar de 38 KB).
- CSS, JS, foto y PDF versionados por contenido (`?v=hash`): se acabó cambiar `?v=2` a mano.
- Enlaces y botones de al menos 44 px de alto; letras de al menos 12 px.
- El botón de idioma declara `lang` y `hreflang`; la sección activa usa `aria-current="location"`.
- Los botones de PDF dicen su peso. Al imprimir, las publicaciones muestran su dirección.
- Faltaba el espacio entre la barra de secciones y el contenido.
- Se enlazan el favicon y el ícono de Apple (existían, pero la página no los usaba).

### Seguridad y operación
- `vercel.json` mezclaba `routes` con `headers`, que Vercel rechaza: ahora usa `rewrites`.
- CSP estricta sin `unsafe-inline` (el script de la barra pasa a `assets/nav.js`), `X-Frame-Options`,
  `Permissions-Policy` y `Cross-Origin-Opener-Policy`.
- La URL canónica, el sitemap y Open Graph ya no dependen del encabezado `Host`: usan
  https://cv-coral-pi.vercel.app (o `SITE_URL`, para un dominio propio), siempre con https.
- Solo GET y HEAD; lo demás responde 405. El 404 lleva `noindex`.
- `/health` ya no anuncia la versión de PHP. El sitemap usa la fecha real de actualización.
- HTML y JSON: un día en la CDN con `stale-while-revalidate` (casi sin arranques en frío).
- JSON-LD con la URL del CV (antes, la de GitHub), foto, `knowsAbout` y afiliación; Open Graph con
  medidas y texto alternativo; `rel="me"` en GitHub, LinkedIn y ORCID.
- Pruebas de humo (`tests/humo.php`, 50 pruebas) en GitHub Actions en cada push.
- `herramientas/rendercv.php`: exporta el CV a RenderCV para generar PDFs adaptados a cada vacante.

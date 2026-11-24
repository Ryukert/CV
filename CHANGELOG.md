# Cambios

## [Sin publicar]

### Revisión previa a producción (ship-gate)

- `app/error.tsx`: si una página falla al generarse, se ve un mensaje propio (español e inglés)
  con enlaces al PDF, sin mostrar el error interno.
- Pruebas en navegador: `puppeteer-core` 24.43.1 → 25.16.0; `npm audit` pasa de 3 dependencias
  vulnerables (basic-ftp, extract-zip, zod; solo en las pruebas, no en el sitio) a 0.
- Se borra `tests/humo.php`, que había quedado de la versión en PHP.
- `.gitignore` incluye `.env*`.
- `DEPLOY.md` explica cómo volver a la versión anterior (Instant Rollback de Vercel y `git revert`).

## 2026-11-24 (íconos) — publicado en main

### HTML más ligero: íconos en un sprite SVG

- Los trazos de los íconos (logotipos de tecnologías, contacto, áreas de enfoque y genéricos) pasan
  a `public/assets/iconos.svg`; la página solo los referencia con `<use href>`. Antes cada trazo
  iba en el HTML y otra vez en los datos internos de React.
- HTML de la página: de 368 KB a 125 KB (comprimido, de 106 KB a 22 KB). El sprite pesa 67 KB y
  el navegador lo guarda un año (cambia de URL si cambia).
- `herramientas/iconos.mjs` genera el sprite y `lib/iconos.ts` (solo nombres, colores e ids).
- Pruebas: cada ícono usa un símbolo que existe en el sprite y el HTML no pasa de 180 KB.

## 2026-11-24 (noche) — publicado en main

### Distribución del sitio y de la información

- Contenedor de 1140 px (antes 900) con cuadrículas que se acomodan solas al ancho de la pantalla.
- Encabezado en dos columnas: datos de contacto y, a la derecha, las **áreas de enfoque** del
  logotipo (IoT, DSP, desarrollo de software, IA, analítica de datos).
- **Acerca de mí** con el resumen y una ficha de idiomas al lado.
- **Logros** en tarjetas; los tres **grupos de la experiencia** lado a lado.
- **Proyectos** en tres columnas; **competencias** en tarjetas por grupo, en dos columnas.
- **Formación** junto a **Otra experiencia** y **Certificaciones**.
- En el celular y al imprimir todo vuelve a una columna. En escritorio la página pasa de 7,550 a
  6,553 px de alto.

## 2026-11-24 (tarde) — publicado en main

### Fondo y paleta del logotipo

- El logotipo «MA» va de fondo en todo el sitio, fijo y atenuado (`fondo.webp`, 117 KB; en el
  celular `fondo-movil.webp`, 57 KB). El blanco de la imagen se hizo transparente para que sirva
  también en el tema oscuro. El original está en `img/fondo-original.png`.
- Paleta del logotipo en todo el sitio: azul marino `#0d2142`, verde `#157a35` (enlaces, botones,
  sección activa) y dorado `#d4a52a` (cargo, línea del encabezado, proyecto destacado). Los títulos
  de sección llevan la línea azul-verde-dorado del logotipo. Tema oscuro con los mismos tonos.
- Favicon y color de la barra del navegador con la nueva paleta.
- Prueba: la huella `?v=` con que el CSS pide el fondo debe ser la del archivo actual.

## 2026-11-24 — publicado en main

### Depuración del contenido

- Fuera la sección **Áreas de la computación** y las **listas de asignaturas** de Formación (las 69
  asignaturas aparecían dos veces). En Formación quedan el título, el promedio y la nota.
- Fuera las **métricas** (200 Hz, 4, 5, 3): repetían los logros clave y el resumen.
- Fuera el proyecto **Micrositio IEPC Guerrero** (maqueta sin backend).
- Competencias: fuera las genéricas que no son herramientas o ya estaban cubiertas (Big Data, Deep
  Learning, Visión artificial, Series temporales, RMS y SQL; queda PostgreSQL).
- El PDF sencillo pasa de 3 páginas llenas a 2½ (90 KB).
- Se quitan el CSS y el JavaScript que solo usaban esas secciones.

### Migración de PHP a Next.js + React (TypeScript)

- El sitio pasa de PHP (runtime de la comunidad `vercel-php`) a **Next.js 16 y React 19** con
  TypeScript, con soporte oficial de Vercel.
- Los datos pasan de `src/data.php` a `datos/compartido.json`, `datos/es.json` y `datos/en.json`.
- **CSP estricta con nonce** por respuesta (`proxy.ts`): sin `unsafe-inline` aunque Next.js necesita
  scripts en línea. Solo GET/HEAD (405 lo demás) y los mismos encabezados de seguridad.
- Las funciones con JavaScript (`cv.js`) se cargan después de que React hidrata la página.
- La generación del PDF (`npm run pdf`), los íconos y las pruebas pasan a Node/TypeScript; GitHub
  Actions compila con Next.js. Ya no hay PHP en el repositorio.
- Mismas rutas y URL: `/`, `/en`, `/api/cv.json`, `/health`, `/sitemap.xml`, `/robots.txt`, `/assets/…`.
- Competencias: TypeScript y Next.js.

### Estilo portafolio y todos los repos de GitHub
- **Competencias con logotipo** de cada tecnología (Simple Icons, CC0, servidos por el sitio); las
  que no tienen logo llevan un ícono por tipo (señales, IA, hardware, datos, APIs, terminal).
- **Proyectos con fecha** (mes de inicio según GitHub) y su fila de tecnologías con ícono; se ordenan
  del más reciente al más antiguo, con el SHM-IoT destacado primero.
- La experiencia muestra las tecnologías que usa.
- Sección **Acerca de mí** con los idiomas y su nivel (salen de Formación).
- **Pie** con el menú de secciones y botones de GitHub, LinkedIn, ORCID y correo.
- Cuatro proyectos nuevos: **Predictor de mortero calibrado**, **SysReview PRISMA**, **Micrositio
  IEPC Guerrero** y **Simulador de red SHM-IoT**. La red SHM georreferenciada se describe con lo que
  hace hoy (9 planteles en Tijuana, 32 edificios en Guerrero, STA/LTA).
- `herramientas/iconos.mjs` genera `src/iconos.php`.

### Logros clave y PDF al día
- Sección **Logros clave** en la web (estaba solo en el PDF), con un quinto logro: la plataforma
  web de riesgo sísmico (5 ciudades, 370 establecimientos CLUES, 152 pruebas automáticas).
- El **PDF sencillo** (`cv-es.pdf`, `cv-en.pdf`) ahora se genera desde `data.php` con RenderCV:
  incluye los proyectos nuevos, las competencias de web y mapas, los logros y las asignaturas;
  3 páginas. `php herramientas/rendercv.php --publicar` lo regenera.
- Prueba que falla si el PDF quedó atrasado respecto a `data.php` (huella en `assets/cv-pdf.json`).

### Funciones con JavaScript
- **Buscador en el CV** y **filtro por competencia**: marcan dónde aparece un término y atenúan lo
  demás; la búsqueda queda en la dirección (`?q=python`) para compartirla.
- **Tema claro/oscuro** manual, recordado en el navegador y sin parpadeo.
- **Copiar correo** y **Compartir**.
- Sin JavaScript la página queda completa (los controles nuevos no aparecen).
- Pruebas en navegador (`tests/navegador`, 29) en GitHub Actions.

### Sin foto
- Se quita la foto de la página, de los datos estructurados y de la API, y sus archivos del sitio.
- La imagen para compartir en redes (`og.jpg`) se rehízo sin foto y va versionada para que las
  redes tomen la nueva.
- En los PDF con diseño, la foto se cambió por un círculo con las iniciales «MA»; el texto no cambió
  (pasan de 295 KB a 51 KB).

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

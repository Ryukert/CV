# CV — Marco Antonio Adame Rodríguez

Sitio web personal / CV en línea, hecho con **Next.js 16 y React 19 en TypeScript** y desplegado en
**Vercel**. Hasta noviembre de 2026 estaba en PHP.

Bilingüe (español e inglés), servido desde una única fuente de datos, con endpoint JSON público.

## Rutas

| Ruta | Descripción |
|---|---|
| `/` | CV en español (HTML) |
| `/en` | CV en inglés (HTML) |
| `/api/cv.json?lang=es\|en` | CV completo en JSON (CORS abierto) |
| `/health` | Estado del servicio y fecha de la última actualización del CV |
| `/assets/cv-es.pdf` | CV en PDF, versión ATS |
| `/assets/cv-es-diseno.pdf` | CV en PDF, versión con diseño |

## Estructura

```text
.
├── datos/
│   ├── compartido.json    # FUENTE ÚNICA: perfil, publicaciones, proyectos, métricas
│   ├── es.json            # textos en español
│   └── en.json            # textos en inglés
├── app/                   # rutas de Next.js (App Router)
│   ├── layout.tsx         # <html>, <head>, tema.js y hoja de estilos
│   ├── page.tsx, en/      # CV en español e inglés
│   ├── not-found.tsx      # 404 con noindex
│   ├── api/cv.json/       # API JSON
│   └── health/, robots.txt/, sitemap.xml/
├── components/
│   ├── Cv.tsx             # el CV completo (componente de servidor)
│   ├── Iconos.tsx         # íconos de contacto y de tecnologías
│   └── ArrancarCv.tsx     # carga cv.js cuando React terminó de hidratar
├── lib/
│   ├── cv-core.ts         # arma el CV (sin dependencias; lo usan web, PDF y pruebas)
│   ├── cv.ts              # capa de datos de la web (URL absolutas, versiones de archivos)
│   ├── rendercv-core.ts   # conversión al formato de RenderCV
│   └── iconos.ts          # GENERADO: logotipos (Simple Icons, CC0)
├── proxy.ts               # CSP con nonce, solo GET/HEAD, encabezados de seguridad
├── public/assets/         # CSS, cv.js, tema.js, fuente, PDFs e imágenes
├── herramientas/
│   ├── rendercv.ts        # genera los PDF y los YAML por vacante
│   ├── iconos.mjs         # genera lib/iconos.ts
│   └── versiones.ts       # huella ?v= de cada archivo (corre antes de dev y build)
├── tests/humo.ts          # pruebas de humo contra la versión compilada
├── tests/navegador/       # pruebas en navegador de las funciones con JavaScript
├── versiones/             # CVs adaptados a cada vacante (solo el README va a git)
└── vercel.json            # framework: nextjs
```

**Para actualizar el CV solo se editan los archivos de `datos/`.** El HTML de ambos idiomas, el JSON
y el PDF salen de ahí. Lo que es igual en los dos idiomas está en `compartido.json`; los textos, en
`es.json` y `en.json` (con las mismas claves: las pruebas lo revisan).

Cada proyecto lleva `date` (mes de inicio, `AAAA-MM`) y se ordena solo del más reciente al más
antiguo. Las tecnologías de `stack` y de las competencias se muestran con su logotipo: si una
tecnología nueva no tiene ícono, se agrega a `SLUGS` en `herramientas/iconos.mjs` y se corre
`npm i --no-save simple-icons@16.30.0 && node herramientas/iconos.mjs`.

## Desarrollo local

Requiere Node.js 22.18 o más nuevo (las herramientas corren TypeScript directo en Node).

```bash
npm install
npm run dev        # http://127.0.0.1:8080
```

## Cómo está hecho

- **Componentes de servidor:** el HTML completo sale del servidor; sin JavaScript la página se ve
  entera.
- **CSP estricta con nonce:** `proxy.ts` genera un nonce distinto en cada respuesta. Next.js pone
  scripts en línea para arrancar React y solo se ejecutan los que llevan ese nonce; no hay
  `unsafe-inline`. Estilos, fuentes e imágenes salen solo del propio sitio.
- **Las funciones interactivas siguen en `public/assets/cv.js`** (JavaScript propio, sin
  bibliotecas). `ArrancarCv.tsx` lo carga *después* de que React hidrata la página: si lo hiciera
  antes, React vería un HTML distinto al del servidor y volvería a pintarlo.
- **Archivos versionados:** `herramientas/versiones.ts` calcula la huella de cada archivo de
  `public/assets` antes de compilar; las URL llevan `?v=<hash>` y se guardan un año en el navegador.
- **URL absolutas** (canonical, sitemap, Open Graph): salen de `SITE_URL` o de `SITIO_PUBLICO` en
  `lib/cv-core.ts`, nunca del encabezado `Host`, que se puede falsificar.

Diferencia con la versión en PHP: por el nonce, el HTML se genera en cada visita y la CDN no lo
guarda (la API JSON sí, un día).

## Funciones con JavaScript (`public/assets/cv.js`)

Mejora progresiva: sin JavaScript la página queda completa y estos controles no aparecen.

- **Buscador:** marca dónde aparece un término (logros, experiencia, proyectos, competencias,
  publicaciones, formación), atenúa lo demás y dice cuántas coincidencias hay por sección. Ignora acentos y mayúsculas. La búsqueda queda en la dirección: `/?q=python` abre ya filtrado.
- **Filtro por competencia:** cada competencia es un botón que filtra por ella y sus variantes
  («Linux / Ubuntu Server» encuentra «servidores Ubuntu»).
- **Tema claro/oscuro:** botón en la barra; la elección se guarda en el navegador.
  `public/assets/tema.js` la aplica antes de pintar para que no parpadee.
- **Copiar correo** y **Compartir** (menú del sistema en el celular; en la computadora copia el enlace).

Los textos de la interfaz están en `datos/es.json` y `datos/en.json` (`ui`).

## Pruebas

```bash
npm run build && npm test                               # humo: rutas, encabezados, contenido, PDF
cd tests/navegador && npm ci && node navegador.mjs      # en Chrome o Edge sin ventana
```

Las de humo levantan la versión compilada y revisan cada ruta: códigos de respuesta, CSP con nonce
(y que cambie en cada respuesta), que todo script en línea lleve el nonce, que existan los archivos
que enlaza la página, JSON-LD, Open Graph, la API JSON, el sitemap, el 404, el 405 y que los dos
idiomas tengan la misma estructura. Las del navegador prueban el buscador, el filtro, el tema (y que
se recuerde), copiar y compartir, que sin JavaScript no aparezcan controles rotos y el celular.
GitHub Actions corre todo en cada push (`.github/workflows/pruebas.yml`).

## Ramas

`main` es lo publicado (Vercel lo despliega). Los cambios se hacen en `feature/…` o `fix/…`, salen de
`develop` y regresan a ella; `develop` pasa a `main` cuando la vista previa está bien. Las ramas no
se borran.

## CV en PDF (RenderCV)

El **PDF sencillo** que descarga el sitio (`cv-es.pdf`, `cv-en.pdf`) se genera desde `datos/` con
[RenderCV](https://rendercv.com), así que trae lo mismo que la web: logros clave, experiencia,
competencias, los proyectos marcados con `"pdf": true`, publicaciones y formación.

```bash
pip install "rendercv[full]"            # una vez
npm run pdf
```

**Cada vez que cambian los datos hay que correrlo.** Guarda en `public/assets/cv-pdf.json` la huella
de los datos con que se generó; si quedó atrasado, `npm test` (y GitHub Actions) falla y dice el
comando.

Los PDF **con diseño** (`cv-es-diseno.pdf`, `cv-en-design.pdf`) están hechos a mano y no se generan.

Para mandar una versión adaptada a una vacante: `versiones/README.md`.

## Despliegue en Vercel

Ver `DEPLOY.md`. La dirección pública es **<https://cv-coral-pi.vercel.app>**; con un dominio propio
se agrega la variable `SITE_URL` (sin `/` al final) en **Settings → Environment Variables**.

## Licencia

Código bajo licencia MIT. El contenido del CV es información personal, no reutilizable.

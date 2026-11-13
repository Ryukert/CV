# CV — Marco Antonio Adame Rodríguez

Sitio web personal / CV en línea, hecho en **PHP** y desplegado en **Vercel** como función serverless.

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

```
.
├── api/
│   └── index.php          # front controller: enruta todas las peticiones
├── src/
│   ├── data.php           # FUENTE ÚNICA DE VERDAD del CV (es + en)
│   ├── helpers.php        # utilidades de escape y formato
│   └── views/
│       ├── layout.php     # plantilla del CV
│       └── 404.php
├── assets/                # CSS, JS (cv.js, tema.js), fuente y PDFs (servidos estáticamente)
│   └── fonts/             # IBM Plex Sans (SIL OFL), servida por el sitio
├── herramientas/
│   └── rendercv.php       # exporta data.php a RenderCV para PDFs por vacante
├── versiones/             # CVs adaptados a cada vacante (solo el README va a git)
├── tests/humo.php         # pruebas de humo (sin dependencias)
├── tests/navegador/       # pruebas en navegador de las funciones con JavaScript
├── .github/workflows/     # pruebas en GitHub Actions
├── router-dev.php         # router solo para desarrollo local
└── vercel.json            # runtime PHP, reescritura a api/index.php y encabezados
```

**Para actualizar el CV solo se edita `src/data.php`.** El HTML de ambos idiomas y el JSON se
regeneran solos. No hay contenido duplicado entre versiones.

## Desarrollo local

Requiere PHP 8.1 o superior instalado.

```bash
php -S 127.0.0.1:8080 router-dev.php
```

Luego abrir http://127.0.0.1:8080

`router-dev.php` solo existe para el servidor embebido de PHP: sirve los archivos de `assets/`
directamente y manda el resto a `api/index.php`, imitando lo que hace Vercel en producción.

## Funciones con JavaScript (`assets/cv.js`)

Mejora progresiva: sin JavaScript la página queda completa y estos controles no aparecen.

- **Buscador:** marca dónde aparece un término (experiencia, proyectos, competencias, publicaciones,
  formación, áreas), atenúa lo demás, abre las asignaturas que coinciden y dice cuántas hay por
  sección. Ignora acentos y mayúsculas. La búsqueda queda en la dirección: `/?q=python` abre ya filtrado.
- **Filtro por competencia:** cada competencia es un botón que filtra por ella y sus variantes
  («Linux / Ubuntu Server» encuentra «servidores Ubuntu»).
- **Tema claro/oscuro:** botón en la barra; la elección se guarda en el navegador. `assets/tema.js` la
  aplica antes de pintar para que no parpadee.
- **Copiar correo** y **Compartir** (menú del sistema en el celular; en la computadora copia el enlace).

Los textos de la interfaz están en `data.php` (`ui`, en los dos idiomas). Todo es JavaScript propio,
sin bibliotecas ni scripts en línea, así que la CSP sigue estricta.

## Pruebas

```bash
php tests/humo.php                                      # sin dependencias
cd tests/navegador && npm ci && node navegador.mjs      # en Chrome o Edge sin ventana
```

Las del navegador prueban el buscador, el filtro, el tema (y que se recuerde), copiar y compartir,
que sin JavaScript no aparezcan controles rotos y el celular.

Levanta el servidor embebido y revisa cada ruta: códigos de respuesta, encabezados de seguridad
(CSP estricta, sin scripts ni estilos en línea), que existan todos los archivos que enlaza la página,
JSON-LD, Open Graph, la API JSON, el sitemap, el 404 y que los dos idiomas tengan la misma estructura.
GitHub Actions las corre en cada push (`.github/workflows/pruebas.yml`).

## Ramas

`main` es lo publicado (Vercel lo despliega). Los cambios se hacen en `feature/…` o `fix/…`, salen de
`develop` y regresan a ella; `develop` pasa a `main` cuando la vista previa está bien. Las ramas no
se borran.

## CV en PDF (RenderCV)

El **PDF sencillo** que descarga el sitio (`assets/cv-es.pdf`, `assets/cv-en.pdf`) se genera desde
`data.php` con [RenderCV](https://rendercv.com), así que trae lo mismo que la web: logros clave,
experiencia, competencias, los proyectos marcados con `'pdf' => true`, publicaciones y asignaturas.

```bash
pip install "rendercv[full]"            # una vez
php herramientas/rendercv.php --publicar
```

**Cada vez que se cambia `data.php` hay que correrlo.** Guarda en `assets/cv-pdf.json` la huella de
los datos con que se generó; si quedó atrasado, `tests/humo.php` (y GitHub Actions) falla y dice el
comando. La conversión está en `src/rendercv.php`.

Los PDF **con diseño** (`cv-es-diseno.pdf`, `cv-en-design.pdf`) están hechos a mano y no se generan.

Para mandar una versión adaptada a una vacante: `versiones/README.md`.

## Rendimiento y seguridad

- Sin recursos de terceros: la fuente, el CSS y el JS se sirven desde el sitio, así que la página no
  espera a Google Fonts ni le envía la IP de los visitantes.
- CSS, JS, foto y PDF llevan `?v=<hash>` según su contenido (`asset()` en `helpers.php`): se guardan un
  año en el navegador y se renuevan solos al cambiar.
- HTML y JSON: una hora en el navegador y un día en la CDN de Vercel, con `stale-while-revalidate`
  para que casi ninguna visita espere el arranque en frío de PHP.
- CSP estricta (`default-src 'self'`, sin `unsafe-inline`), `frame-ancestors 'none'`,
  `Permissions-Policy` y solo GET/HEAD (lo demás responde 405).

## Despliegue en Vercel

1. Subir este repositorio a GitHub.
2. En [vercel.com](https://vercel.com), **Add New → Project** e importar el repositorio.
3. En *Framework Preset* elegir **Other**. No configurar comando de build ni directorio de salida.
4. La dirección pública es **https://cv-coral-pi.vercel.app** (`SITIO_PUBLICO` en `src/helpers.php`);
   con ella se arman la URL canónica, el sitemap y Open Graph, nunca con el encabezado `Host` de la
   petición, que se puede falsificar. Con un dominio propio, se agrega en **Settings → Environment
   Variables** la variable `SITE_URL` (sin `/` al final) o se cambia `SITIO_PUBLICO`.
5. **Deploy**.

Vercel lee `vercel.json`, instala el runtime `vercel-php@0.9.0` (PHP 8.5) y publica el sitio.
Cada `git push` a la rama principal genera un despliegue nuevo automáticamente.

### Notas sobre el runtime

`vercel-php` es un runtime **mantenido por la comunidad** (`vercel-community/php`), no un producto
oficial de Vercel. Funciona bien, pero conviene tenerlo presente:

- La versión está fijada en `vercel.json`. Si un despliegue empieza a fallar sin que se haya
  cambiado el código, lo primero a revisar es si esa versión sigue publicada.
- Las funciones serverless tienen arranque en frío: la primera visita tras un periodo de
  inactividad puede tardar un poco más.
- No hay estado entre peticiones ni sistema de archivos persistente. Este sitio no lo necesita.

## Dominio propio

En Vercel: **Settings → Domains → Add**. Si aún no hay dominio, la URL
`nombre-del-proyecto.vercel.app` funciona perfectamente y es la que puede ir en el CV en PDF.

## Licencia

Código bajo licencia MIT. El contenido del CV es información personal, no reutilizable.

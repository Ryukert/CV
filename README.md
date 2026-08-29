# Mi CV en línea

Esta es mi hoja de vida como sitio web, en español e inglés. Está hecha en PHP y corre en Vercel.

Producción: `https://<tu-proyecto>.vercel.app` (actualiza esta línea cuando tengas la URL)

## Por qué PHP y no un HTML suelto

La primera versión la hice en HTML plano y terminé con el mismo contenido escrito dos veces, una
por idioma. Cada vez que cambiaba algo del CV tenía que acordarme de tocar los dos archivos, y
más de una vez se me quedó uno desactualizado.

Con PHP todo el contenido vive en `src/data.php` y las páginas se arman desde ahí. Cambio un dato,
hago push, y los dos idiomas más el JSON quedan iguales. Es un proyecto chico, pero era eso o
seguir copiando y pegando.

De paso aproveché para exponer el CV como JSON en `/api/cv.json`, porque ya que hay servidor,
tenerlo disponible en un formato consumible no cuesta nada.

## Rutas

| Ruta | Qué hace |
|---|---|
| `/` | CV en español |
| `/en` | CV en inglés |
| `/api/cv.json?lang=es\|en` | El CV completo en JSON, con CORS abierto |
| `/sitemap.xml`, `/robots.txt` | Generados por PHP, con el dominio que detecte de la petición |
| `/health` | Responde `ok` y la versión de PHP. Lo uso para saber si el deploy quedó bien |
| `/assets/cv-es.pdf` | El CV en PDF. También está la versión con diseño y las dos en inglés |

## Cómo está organizado

```
api/index.php        Front controller. Todo entra por aquí (así lo enruta vercel.json)
src/data.php         Todo el contenido del CV, es y en. Es lo único que edito normalmente
src/helpers.php      Escape de HTML y un par de utilidades
src/views/           Las plantillas
assets/              CSS, foto, PDFs, favicon e imagen de previsualización
router-dev.php       Solo para desarrollo local, ver abajo
vercel.json          Runtime de PHP y reglas de rutas
```

## Correrlo en local

Necesitas PHP 8.1 o superior.

```bash
php -S 127.0.0.1:8080 router-dev.php
```

`router-dev.php` existe porque el servidor embebido de PHP no sabe de `vercel.json`. Lo que hace
es servir los archivos de `assets/` tal cual y mandar todo lo demás a `api/index.php`, que es el
mismo comportamiento que tengo en producción. En Vercel ese archivo no se usa.

## Desplegarlo

1. Subir el repo a GitHub.
2. En Vercel: Add New → Project → importar el repo.
3. En Framework Preset elegir **Other**. Sin build command ni output directory.
4. Deploy.

Vercel lee `vercel.json`, jala el runtime de PHP y publica. Después de eso cada push a `main`
genera un deploy nuevo solo.

### Sobre el runtime de PHP

Vercel no soporta PHP oficialmente. Esto funciona con
[`vercel-community/php`](https://github.com/vercel-community/php), que es un runtime mantenido por
la comunidad. Va bien, pero hay que tenerlo presente:

- La versión está fijada en `vercel.json` (`vercel-php@0.9.0`, PHP 8.5). Si un día el deploy
  truena sin que yo haya tocado nada, lo primero que reviso es si esa versión sigue publicada.
- Son funciones serverless, así que la primera visita después de un rato inactivo tarda un poco más.
- No hay estado ni disco persistente entre peticiones. Para esto no hace falta.

Si algún día necesito PHP con estado o con base de datos, esto se va a otro lado. Para un CV
estático servido por PHP funciona sin problema.

### Si algo falla

Los errores reales de PHP salen en Vercel, en Deployments → el deploy → Functions → Logs. Si
`/health` responde pero `/` no, el problema está en las vistas. Si no responde nada, es el
runtime o el `vercel.json`.

Si el CSS no carga, casi siempre es que `assets/` no se subió a GitHub por algo en el `.gitignore`.

## Pendientes

- Leer mis repos en vivo desde la API de GitHub. Lo dejé fuera porque sin token son 60 peticiones
  por hora por IP y en Vercel las IPs son compartidas, así que fallaría a ratos. Con un token en
  variables de entorno y un caché corto se resuelve.
- Dominio propio en lugar del subdominio de Vercel.

## Licencia

El código es MIT, tómalo si te sirve. El contenido del CV es información mía y esa no.

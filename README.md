# CV — Marco Antonio Adame Rodríguez

Sitio web personal / CV en línea, hecho en **PHP** y desplegado en **Vercel** como función serverless.

Bilingüe (español e inglés), servido desde una única fuente de datos, con endpoint JSON público.

## Rutas

| Ruta | Descripción |
|---|---|
| `/` | CV en español (HTML) |
| `/en` | CV en inglés (HTML) |
| `/api/cv.json?lang=es\|en` | CV completo en JSON (CORS abierto) |
| `/health` | Estado del servicio y versión de PHP |
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
├── assets/                # CSS, foto y PDFs (servidos estáticamente)
├── router-dev.php         # router solo para desarrollo local
└── vercel.json            # runtime PHP + reglas de enrutado
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

## Despliegue en Vercel

1. Subir este repositorio a GitHub.
2. En [vercel.com](https://vercel.com), **Add New → Project** e importar el repositorio.
3. En *Framework Preset* elegir **Other**. No configurar comando de build ni directorio de salida.
4. **Deploy**.

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

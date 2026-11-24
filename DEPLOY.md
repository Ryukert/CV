# Publicar en Vercel

El repositorio ya está en GitHub (`Ryukert/CV`) y conectado a Vercel. Vercel despliega `main`
en cada push y crea una vista previa por cada rama.

## Al pasar de PHP a Next.js (una sola vez)

`vercel.json` ya dice `"framework": "nextjs"`, así que Vercel compila con `npm run build`. Aun así,
conviene revisar en **Vercel → el proyecto → Settings → Build and Deployment**:

1. *Framework Preset*: **Next.js** (antes era *Other*).
2. *Build Command*, *Output Directory* e *Install Command*: vacíos (los valores de Next.js).
3. *Node.js Version*: **22.x** o más nueva (las herramientas usan TypeScript directo en Node).

La variable `SITE_URL` (si existe) sigue funcionando igual.

## Comprobar que todo quedó bien

Abre estas rutas en la vista previa de la rama antes de unirla a `main`:

- `/` → CV en español
- `/en` → CV en inglés
- `/health` → debe responder `{"status":"ok","cv_updated":"AAAA-MM-DD"}`
- `/api/cv.json` → el CV en JSON
- `/assets/cv-es.pdf` → descarga del PDF

En las herramientas del navegador (consola) no debe aparecer ningún aviso de la CSP.

## Errores comunes

**El despliegue falla en «Running TypeScript»**
Corre `npm run build` en tu computadora: muestra el mismo error con el archivo y la línea.

**Página en blanco o error 500**
En Vercel: *Deployments → el despliegue → Logs*. Ahí aparece el error real.

**Los botones (buscador, tema, copiar) no aparecen**
Abre la consola del navegador: si dice que la CSP bloqueó un script, revisa `proxy.ts`.

**El CSS no carga**
Verifica que `public/assets/` se haya subido a GitHub.

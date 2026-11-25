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

## Proteger las vistas previas (una sola vez)

Cada rama (`develop`, `feature/…`, `release/…`) genera una vista previa con su propia dirección.
Para que solo tú puedas verlas y no se filtren versiones sin publicar:

1. Vercel → el proyecto del CV → **Settings → Deployment Protection**.
2. **Vercel Authentication**: activado, en **Standard Protection** (protege las vistas previas;
   la dirección pública `cv-coral-pi.vercel.app` sigue abierta).
3. Guardar y abrir la dirección de una vista previa en una ventana privada: debe pedir iniciar
   sesión en Vercel.

## Monitor de disponibilidad

`.github/workflows/monitor.yml` revisa el sitio cada 30 minutos desde GitHub Actions. Si falla,
GitHub manda un correo. Se puede correr a mano en GitHub → **Actions → Monitor → Run workflow**.
GitHub lo pausa si el repositorio pasa 60 días sin cambios; se reactiva desde la misma pestaña.

## Volver a la versión anterior (si algo sale mal en producción)

1. **Inmediato, sin tocar el código:** Vercel → el proyecto → *Deployments* → el último despliegue
   que funcionaba → menú «⋯» → **Instant Rollback** (o *Promote to Production*). El sitio vuelve
   en segundos.
2. **Después, en git:** deshacer el cambio en `main` con un commit nuevo y subirlo:

   ```bash
   git switch main
   git revert -m 1 <commit «Publica develop: …» que falló>
   git push origin main
   ```

   Luego llevar el mismo revert a `develop` (`git switch develop && git merge main`).
3. Comprobar: `/health` responde `{"status":"ok",…}` y la página se ve bien.

Antes de publicar, la vista previa de `develop` en Vercel y GitHub Actions (tipos, compilación,
pruebas de humo y de navegador) hacen de entorno de prueba.

## Errores comunes

**El despliegue falla en «Running TypeScript»**
Corre `npm run build` en tu computadora: muestra el mismo error con el archivo y la línea.

**Página en blanco o error 500**
En Vercel: *Deployments → el despliegue → Logs*. Ahí aparece el error real.

**Los botones (buscador, tema, copiar) no aparecen**
Abre la consola del navegador: si dice que la CSP bloqueó un script, revisa `proxy.ts`.

**El CSS no carga**
Verifica que `public/assets/` se haya subido a GitHub.

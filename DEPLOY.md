# Pasos exactos para publicarlo

## 1. Crear el repositorio en GitHub

En https://github.com/new → nombre sugerido: `cv-marco-adame` → Public → **Create repository**.
No marques "Add a README" (este proyecto ya trae uno).

## 2. Subir los archivos desde tu computadora

Descomprime el archivo `cv-web.zip`, abre una terminal dentro de la carpeta y ejecuta:

```bash
git init
git add .
git commit -m "CV en PHP desplegable en Vercel"
git branch -M main
git remote add origin https://github.com/Ryukert/cv-marco-adame.git
git push -u origin main
```

Cambia la URL si le pusiste otro nombre al repositorio.

## 3. Conectar con Vercel

1. Entra a https://vercel.com e inicia sesión con tu cuenta de GitHub.
2. **Add New → Project**.
3. Busca `cv-marco-adame` e **Import**.
4. En *Framework Preset* selecciona **Other**.
5. Deja vacíos *Build Command* y *Output Directory*.
6. **Deploy**.

En un minuto tendrás una URL del tipo `https://cv-marco-adame.vercel.app`.

## 4. Comprobar que todo quedó bien

Abre estas rutas en el navegador:

- `/` → CV en español
- `/en` → CV en inglés
- `/health` → debe responder `{"status":"ok","php":"8.5..."}`
- `/api/cv.json` → tu CV en JSON
- `/assets/cv-es.pdf` → descarga del PDF

Si `/health` responde pero `/` no, el problema está en las vistas.
Si nada responde, revisa el log del despliegue en Vercel: pestaña **Deployments → Functions**.

## 5. Ponerlo a trabajar

- Agrega la URL a tu LinkedIn, en el campo *Sitio web*.
- Ponla en el encabezado de tu CV en PDF, junto a GitHub y ORCID.
- Fíjala en tu perfil de GitHub (Pinned repositories) para que sea lo primero que se vea.

## Errores comunes

**"The Runtime vercel-php@0.9.0 is not found"**
La versión ya no está publicada. Revisa https://github.com/vercel-community/php/releases
y actualiza el número en `vercel.json`.

**Página en blanco sin error**
En Vercel: *Deployments → el despliegue → Functions → Logs*. Ahí aparece el error real de PHP.

**El CSS no carga**
Verifica que la carpeta `assets/` sí se haya subido a GitHub. Un `.gitignore` mal puesto
es la causa habitual.

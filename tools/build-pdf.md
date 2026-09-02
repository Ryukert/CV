# Regenerar los PDF con diseño

Los archivos `assets/cv-es-diseno.pdf` y `assets/cv-en-design.pdf` **no se editan a mano**.
Se generan desde `src/data.php`, igual que el sitio, así que basta editar los datos y
volver a exportarlos para que las cuatro piezas (HTML español, HTML inglés y los dos PDF)
digan lo mismo.

## Cómo

1. Levanta el servidor local:

   ```bash
   php -S localhost:8000 router-dev.php
   ```

2. Abre en el navegador y exporta a PDF (Ctrl/Cmd + P → Guardar como PDF):

   - Español: <http://localhost:8000/print?lang=es>
   - Inglés:  <http://localhost:8000/print?lang=en>

   Ajustes de impresión: tamaño **A4**, márgenes **ninguno**, y activar
   **gráficos de fondo** (si no, se pierde la banda oscura y la traza).

3. Guarda los archivos como `assets/cv-es-diseno.pdf` y `assets/cv-en-design.pdf`.

## Alternativa sin abrir el navegador

Con Chrome o Chromium instalado:

```bash
chromium --headless --disable-gpu --no-pdf-header-footer \
  --print-to-pdf=assets/cv-es-diseno.pdf \
  'http://localhost:8000/print?lang=es'
```

## Los otros dos PDF

`assets/cv-es.pdf` y `assets/cv-en.pdf` son la versión sobria, sin color de fondo, pensada
para sistemas de seguimiento de candidatos (ATS) que leen mal los PDF muy maquetados.
Esos salen de los documentos de Word, no de este sitio; si cambias datos aquí, acuérdate
de actualizarlos también.

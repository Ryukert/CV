# Versiones del CV por vacante

Cada carpeta es una versión del CV adaptada a una vacante, generada desde `datos/*.json` con
[RenderCV](https://rendercv.com). Parten de todos los proyectos y sin asignaturas, para recortar y
reordenar según la vacante. El PDF que descarga el sitio se genera aparte, con `npm run pdf`.

```bash
pip install "rendercv[full]"                                     # una vez
node herramientas/rendercv.ts es --version=acme-iot              # crea versiones/acme-iot/cv-es.yaml
# editar el YAML: orden de competencias, palabras clave de la vacante, proyectos a destacar
python -m rendercv render versiones/acme-iot/cv-es.yaml --pdf-path cv-es.pdf
```

`node herramientas/rendercv.ts es --pdf` hace los dos pasos con la versión `base` (sin adaptar).

Las carpetas de versiones no se suben a git (ver `.gitignore`): pueden llevar notas sobre la
empresa o la vacante. Lleva aquí el registro de lo que mandaste:

| Versión | Vacante | Fecha | Estado |
|---|---|---|---|
| base | Sin adaptar | 2026-11-13 | Lista |

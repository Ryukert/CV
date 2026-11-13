<?php
declare(strict_types=1);

/**
 * Genera el CV en PDF con RenderCV (https://rendercv.com) desde src/data.php.
 *
 *   php herramientas/rendercv.php --publicar
 *       Regenera los PDF sencillos que descarga el sitio (assets/cv-es.pdf y
 *       assets/cv-en.pdf) y su huella (assets/cv-pdf.json). Hay que correrlo cada
 *       vez que cambia data.php: tests/humo.php falla si el PDF quedó atrasado.
 *
 *   php herramientas/rendercv.php es --version=acme-iot [--pdf]
 *       Crea versiones/acme-iot/cv-es.yaml (todos los proyectos, sin asignaturas)
 *       para adaptarlo a una vacante; con --pdf también genera el PDF.
 *
 * Necesita RenderCV: pip install "rendercv[full]". Si «python» no es el comando,
 * usar la variable PYTHON.
 *
 * El YAML se escribe como JSON, que también es YAML válido: así no hace falta
 * ninguna biblioteca de YAML en PHP.
 */

require __DIR__ . '/../src/data.php';
require __DIR__ . '/../src/helpers.php';
require __DIR__ . '/../src/rendercv.php';

$raiz = dirname(__DIR__);
$args = array_slice($argv, 1);
$sitio = rtrim((string) getenv('SITE_URL'), '/') ?: SITIO_PUBLICO;

/** Escribe el YAML y, si se pide, corre RenderCV. Devuelve la ruta del PDF o del YAML. */
function generar(array $cv, string $dir, string $nombre, bool $pdf): string
{
    if (!is_dir($dir) && !mkdir($dir, 0777, true)) {
        fwrite(STDERR, "No se pudo crear $dir\n");
        exit(1);
    }
    $yaml = "$dir/$nombre.yaml";
    file_put_contents($yaml, "# Generado por herramientas/rendercv.php desde src/data.php.\n# Se puede editar para adaptarlo a una vacante; volver a generarlo lo sobrescribe.\n"
        . json_encode($cv, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\n");
    if (!$pdf) {
        return $yaml;
    }
    // RenderCV toma las rutas de salida relativas a la carpeta del YAML.
    $cmd = [getenv('PYTHON') ?: 'python', '-m', 'rendercv', 'render', $yaml, '--pdf-path', "$nombre.pdf", '--typst-path', "$nombre.typ",
            '--dont-generate-markdown', '--dont-generate-html', '--dont-generate-png', '--quiet'];
    $p = proc_open($cmd, [1 => STDOUT, 2 => STDERR], $pipes, $dir);
    $codigo = is_resource($p) ? proc_close($p) : 1;
    if ($codigo !== 0 || !is_file("$dir/$nombre.pdf")) {
        fwrite(STDERR, "RenderCV falló (código $codigo). ¿Está instalado? pip install \"rendercv[full]\"\n");
        exit(1);
    }
    return "$dir/$nombre.pdf";
}

if (in_array('--publicar', $args, true)) {
    $tmp = "$raiz/versiones/.publicar";
    $huellas = [];
    foreach (['es', 'en'] as $lang) {
        $pdf = generar(cv_rendercv($lang, true, $sitio), $tmp, "cv-$lang", true);
        copy($pdf, "$raiz/assets/cv-$lang.pdf");
        $huellas[$lang] = cv_rendercv_huella($lang, $sitio);
        echo "assets/cv-$lang.pdf (" . round(filesize("$raiz/assets/cv-$lang.pdf") / 1024) . " KB)\n";
    }
    file_put_contents("$raiz/assets/cv-pdf.json", json_encode(['generado_con' => 'herramientas/rendercv.php --publicar', 'cv_updated' => CV_UPDATED, 'sitio' => $sitio, 'huellas' => $huellas], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
    array_map('unlink', glob("$tmp/*") ?: []);
    @rmdir($tmp);
    echo "Huella: assets/cv-pdf.json\n";
    exit(0);
}

$lang = in_array('en', $args, true) ? 'en' : 'es';
$version = 'base';
foreach ($args as $a) {
    if (preg_match('/^--version=([a-z0-9][a-z0-9-]{0,60})$/', $a, $m)) {
        $version = $m[1];
    }
}
$salida = generar(cv_rendercv($lang, false, $sitio), "$raiz/versiones/$version", "cv-$lang", in_array('--pdf', $args, true));
echo (str_ends_with($salida, '.pdf') ? 'PDF:  ' : 'YAML: ') . $salida . "\n";

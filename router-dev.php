<?php
// Router para el servidor embebido de PHP (solo desarrollo local).
// Sirve archivos estáticos reales y manda todo lo demás a api/index.php.
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;
if ($path !== '/' && file_exists($file) && !is_dir($file)) {
    return false;
}
require __DIR__ . '/api/index.php';

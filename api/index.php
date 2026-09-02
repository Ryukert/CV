<?php
declare(strict_types=1);

/**
 * Front controller. Todas las peticiones entran aquí (ver vercel.json).
 *
 * Rutas:
 *   GET /            → CV en español (HTML)
 *   GET /en          → CV en inglés (HTML)
 *   GET /api/cv.json → CV completo en JSON (?lang=es|en)
 *   GET /health      → estado del servicio
 */

require __DIR__ . '/../src/data.php';
require __DIR__ . '/../src/helpers.php';

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$path = rtrim($path, '/');
if ($path === '') {
    $path = '/';
}

header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');

switch ($path) {
    case '/':
        render_page('es');
        break;

    case '/en':
        render_page('en');
        break;

    case '/api/cv.json':
        $lang = ($_GET['lang'] ?? 'es') === 'en' ? 'en' : 'es';
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Cache-Control: public, max-age=300');
        echo json_encode(cv_data($lang), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        break;

    case '/health':
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status'      => 'ok',
            'php'         => PHP_VERSION,
            'generated_at'=> gmdate('c'),
        ]);
        break;

    default:
        http_response_code(404);
        render_404();
        break;
}

function render_page(string $lang): void
{
    $d = cv_data($lang);
    header('Content-Type: text/html; charset=utf-8');
    header('Cache-Control: public, max-age=600, s-maxage=3600');
    require __DIR__ . '/../src/views/layout.php';
}

function render_404(): void
{
    header('Content-Type: text/html; charset=utf-8');
    require __DIR__ . '/../src/views/404.php';
}

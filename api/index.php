<?php
declare(strict_types=1);

/**
 * Front controller. Todas las peticiones entran aquí (ver vercel.json).
 *
 * Rutas:
 *   GET /             → CV en español (HTML)
 *   GET /en           → CV en inglés (HTML)
 *   GET /api/cv.json  → CV completo en JSON (?lang=es|en)
 *   GET /sitemap.xml  → sitemap para buscadores
 *   GET /robots.txt   → reglas de rastreo
 *   GET /health       → estado del servicio
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

    case '/sitemap.xml':
        header('Content-Type: application/xml; charset=utf-8');
        $today = gmdate('Y-m-d');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach (['/' => 'es', '/en' => 'en'] as $loc => $lang) {
            echo "  <url><loc>" . base_url() . $loc . "</loc>"
               . "<lastmod>{$today}</lastmod>"
               . "<changefreq>monthly</changefreq></url>\n";
        }
        echo '</urlset>';
        break;

    case '/robots.txt':
        header('Content-Type: text/plain; charset=utf-8');
        echo "User-agent: *\nAllow: /\n\nSitemap: " . base_url() . "/sitemap.xml\n";
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

/** URL base del despliegue, tomada de las cabeceras de la petición. */
function base_url(): string
{
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $proto = (($_SERVER['HTTPS'] ?? '') === 'on' || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
        ? 'https' : 'http';
    return $proto . '://' . $host;
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

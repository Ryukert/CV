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

    case '/print':
        // Hoja A4 con diseño; se convierte a PDF con Chromium (tools/build-pdf.md).
        $lang = ($_GET['lang'] ?? 'es') === 'en' ? 'en' : 'es';
        $d = cv_data($lang);
        header('Content-Type: text/html; charset=utf-8');
        header('X-Robots-Tag: noindex');
        require __DIR__ . '/../src/views/print.php';
        break;

    case '/robots.txt':
        header('Content-Type: text/plain; charset=utf-8');
        header('Cache-Control: public, max-age=86400');
        echo "User-agent: *\nAllow: /\n\nSitemap: " . site_url('/sitemap.xml') . "\n";
        break;

    case '/sitemap.xml':
        header('Content-Type: application/xml; charset=utf-8');
        header('Cache-Control: public, max-age=86400');
        $today = gmdate('Y-m-d');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";
        foreach (['/' => 'es', '/en' => 'en'] as $loc => $hl) {
            echo "  <url>\n    <loc>", htmlspecialchars(site_url($loc), ENT_XML1), "</loc>\n";
            echo '    <xhtml:link rel="alternate" hreflang="es" href="', htmlspecialchars(site_url('/'), ENT_XML1), "\"/>\n";
            echo '    <xhtml:link rel="alternate" hreflang="en" href="', htmlspecialchars(site_url('/en'), ENT_XML1), "\"/>\n";
            echo "    <lastmod>{$today}</lastmod>\n    <changefreq>monthly</changefreq>\n  </url>\n";
        }
        echo "</urlset>\n";
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

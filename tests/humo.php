<?php
declare(strict_types=1);

/**
 * Pruebas de humo del CV: levanta el servidor embebido de PHP con router-dev.php
 * (igual que en desarrollo) y revisa cada ruta como la vería un navegador.
 *
 *   php tests/humo.php
 *
 * Sin dependencias: solo PHP 8.1+ con las extensiones dom y json (vienen por omisión).
 * Sale con código 1 si alguna prueba falla.
 */

$raiz   = dirname(__DIR__);
$puerto = (int) (getenv('PUERTO') ?: 8098);
$base   = "http://127.0.0.1:$puerto";

$proc = proc_open(
    [PHP_BINARY, '-S', "127.0.0.1:$puerto", 'router-dev.php'],
    [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
    $pipes,
    $raiz
);
if (!is_resource($proc)) {
    fwrite(STDERR, "No se pudo levantar el servidor de PHP\n");
    exit(1);
}
register_shutdown_function(static function () use ($proc): void { proc_terminate($proc); });

// Espera a que el servidor responda (hasta 5 s).
for ($i = 0; $i < 50 && @fsockopen('127.0.0.1', $puerto) === false; $i++) {
    usleep(100_000);
}

$total = 0;
$fallas = 0;
function ok(bool $cond, string $texto): void
{
    global $total, $fallas;
    $total++;
    if (!$cond) {
        $fallas++;
    }
    echo ($cond ? '  ok    ' : '  FALLA ') . $texto . "\n";
}

/** @return array{status:int, headers:array<string,string>, body:string} */
function pedir(string $url, string $metodo = 'GET'): array
{
    $ctx = stream_context_create(['http' => ['method' => $metodo, 'ignore_errors' => true, 'timeout' => 10]]);
    $body = (string) @file_get_contents($url, false, $ctx);
    $headers = [];
    $status = 0;
    foreach ($http_response_header ?? [] as $h) {
        if (preg_match('#^HTTP/\S+ (\d{3})#', $h, $m)) {
            $status = (int) $m[1];
        } elseif (str_contains($h, ':')) {
            [$k, $v] = explode(':', $h, 2);
            $headers[strtolower(trim($k))] = trim($v);
        }
    }
    return ['status' => $status, 'headers' => $headers, 'body' => $body];
}

function dom(string $html): DOMXPath
{
    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML('<?xml encoding="utf-8"?>' . $html);
    libxml_clear_errors();
    return new DOMXPath($doc);
}

require $raiz . '/src/data.php';

// ── Páginas del CV en los dos idiomas ──
foreach (['/' => 'es', '/en' => 'en'] as $ruta => $lang) {
    $r = pedir($base . $ruta);
    $d = cv_data($lang);
    ok($r['status'] === 200 && str_starts_with($r['headers']['content-type'] ?? '', 'text/html'), "$ruta responde 200 en HTML");
    $csp = $r['headers']['content-security-policy'] ?? '';
    ok(str_contains($csp, "script-src 'self'") && str_contains($csp, "frame-ancestors 'none'") && !str_contains($csp, 'unsafe-inline'), "$ruta trae CSP estricta (sin unsafe-inline)");
    ok(($r['headers']['x-frame-options'] ?? '') === 'DENY' && isset($r['headers']['permissions-policy']), "$ruta trae X-Frame-Options y Permissions-Policy");
    ok(str_contains($r['headers']['cache-control'] ?? '', 's-maxage=86400') && str_contains($r['headers']['cache-control'] ?? '', 'stale-while-revalidate'), "$ruta se guarda un día en la CDN");
    ok(!str_contains($r['body'], '<?php') && !preg_match('/(Warning|Notice|Deprecated|Fatal error):/', $r['body']), "$ruta sin código PHP ni avisos en la salida");

    $x = dom($r['body']);
    ok($x->query('//html[@lang="' . $lang . '"]')->length === 1, "$ruta declara lang=\"$lang\"");
    ok($x->query('//h1')->length === 1, "$ruta tiene un solo h1");
    ok($x->query('//script[not(@src) and not(@type="application/ld+json")]')->length === 0 && $x->query('//*[@style or @onclick or @onload]')->length === 0, "$ruta sin scripts ni estilos en línea (los bloquearía la CSP)");
    ok($x->query('//link[contains(@href,"fonts.googleapis") or contains(@href,"gstatic")]')->length === 0, "$ruta no carga nada de Google Fonts");
    ok($x->query('//img[not(@alt) or not(@width) or not(@height)]')->length === 0, "$ruta: toda imagen tiene alt, width y height");

    // Todo recurso propio que pide la página existe en assets/.
    $faltan = [];
    foreach ($x->query('//*[@href or @src or @srcset]') as $n) {
        foreach (['href', 'src', 'srcset'] as $at) {
            foreach (preg_split('/,\s*/', $n->getAttribute($at)) as $parte) {
                $url = strtok(trim($parte), ' ');
                if ($url && str_starts_with($url, '/assets/')) {
                    $archivo = $raiz . parse_url($url, PHP_URL_PATH);
                    if (!is_file($archivo)) {
                        $faltan[] = $url;
                    }
                }
            }
        }
    }
    ok($faltan === [], "$ruta: existen todos los archivos de /assets/ que enlaza" . ($faltan ? ' (faltan ' . implode(', ', $faltan) . ')' : ''));
    ok(preg_match_all('#/assets/(style\.css|nav\.js|foto-\d+\.\w+|cv-[\w-]+\.pdf)\?v=[0-9a-f]{8}#', $r['body']) >= 5, "$ruta versiona CSS, JS, foto y PDF por contenido (?v=hash)");

    // JSON-LD válido y con la URL del CV (no la de GitHub).
    $ld = $x->query('//script[@type="application/ld+json"]')->item(0);
    $json = $ld ? json_decode($ld->textContent, true) : null;
    ok(is_array($json) && ($json['@type'] ?? '') === 'Person' && !str_contains($json['url'] ?? 'github', 'github.com'), "$ruta: JSON-LD Person válido con la URL del CV");

    ok($x->query('//a[@rel="me noopener"]')->length === 3, "$ruta: GitHub, LinkedIn y ORCID con rel=\"me\"");
    $otro = $lang === 'es' ? 'en' : 'es';
    ok($x->query('//a[@hreflang="' . $otro . '" and @lang="' . $otro . '"]')->length >= 1, "$ruta: el enlace al otro idioma declara lang y hreflang");
    ok($x->query('//meta[@property="og:image:width"]')->length === 1 && $x->query('//meta[@property="og:image:alt"]')->length === 1, "$ruta: Open Graph con medidas y texto alternativo de la imagen");

    // Contenido: cada proyecto y cada sección del menú aparecen.
    foreach ($d['projects'] as $p) {
        [$titulo] = $d['project_text'][$p['key']];
        if (!str_contains($r['body'], htmlspecialchars($titulo, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'))) {
            ok(false, "$ruta muestra el proyecto «{$titulo}»");
        }
    }
    ok(count($d['projects']) === count($d['project_text']), "$ruta: todos los proyectos tienen texto (" . count($d['projects']) . ')');
    foreach (array_keys($d['nav']) as $id) {
        if ($x->query('//section[@id="' . $id . '"]')->length !== 1) {
            ok(false, "$ruta: existe la sección #$id del menú");
        }
    }
}

// ── Los dos idiomas tienen la misma estructura ──
$es = cv_data('es');
$en = cv_data('en');
ok(array_keys($es) == array_keys($en), 'español e inglés tienen las mismas claves');
ok(count($es['experience'][0]['groups']) === count($en['experience'][0]['groups']) && count($es['skills']) === count($en['skills']), 'misma cantidad de grupos de experiencia y de competencias');
ok(array_keys($es['project_text']) == array_keys($en['project_text']), 'los mismos proyectos en los dos idiomas');

// ── API JSON ──
$r = pedir("$base/api/cv.json?lang=en");
$j = json_decode($r['body'], true);
ok($r['status'] === 200 && is_array($j) && $j['lang'] === 'en', '/api/cv.json?lang=en devuelve el CV en inglés');
ok(($r['headers']['access-control-allow-origin'] ?? '') === '*', '/api/cv.json se puede leer desde otros sitios (CORS)');
ok(($j['profile']['name'] ?? '') === PROFILE['name'], '/api/cv.json trae el perfil');
$r = pedir("$base/api/cv.json?lang=xx");
ok((json_decode($r['body'], true)['lang'] ?? '') === 'es', 'un idioma desconocido cae en español');

// ── Rutas auxiliares ──
$r = pedir("$base/health");
$h = json_decode($r['body'], true);
ok($r['status'] === 200 && ($h['status'] ?? '') === 'ok' && !isset($h['php']), '/health responde ok sin anunciar la versión de PHP');
$r = pedir("$base/sitemap.xml");
ok($r['status'] === 200 && str_contains($r['body'], '<lastmod>' . CV_UPDATED . '</lastmod>'), '/sitemap.xml usa la fecha real de actualización (' . CV_UPDATED . ')');
ok(@simplexml_load_string($r['body']) !== false, '/sitemap.xml es XML válido');
$r = pedir("$base/robots.txt");
ok($r['status'] === 200 && str_contains($r['body'], 'Sitemap: '), '/robots.txt apunta al sitemap');
$r = pedir("$base/no-existe");
ok($r['status'] === 404 && str_contains($r['body'], 'noindex'), 'una ruta desconocida da 404 con noindex');
$r = pedir("$base/", 'POST');
ok($r['status'] === 405 && ($r['headers']['allow'] ?? '') === 'GET, HEAD', 'POST responde 405 (el sitio no recibe datos)');

// ── SITE_URL: la URL canónica no depende del Host de la petición ──
putenv('SITE_URL=https://cv.ejemplo.dev');
$_SERVER['HTTP_HOST'] = 'atacante.example';
require_once $raiz . '/src/helpers.php';
ok(site_url('/en') === 'https://cv.ejemplo.dev/en', 'con SITE_URL, las URL absolutas ignoran un Host falso');
putenv('SITE_URL');

// ── Archivos ──
foreach (['cv-es.pdf', 'cv-en.pdf', 'cv-es-diseno.pdf', 'cv-en-design.pdf', 'og.jpg', 'foto-150.webp', 'foto-300.webp', 'foto-300.jpg', 'fonts/ibm-plex-sans-400.woff2'] as $f) {
    if (!is_file("$raiz/assets/$f")) {
        ok(false, "existe assets/$f");
    }
}
ok(filesize("$raiz/assets/foto-150.webp") < 15_000, 'la foto que se muestra pesa menos de 15 KB');
$vercel = json_decode((string) file_get_contents("$raiz/vercel.json"), true);
ok(is_array($vercel) && !(isset($vercel['routes']) && (isset($vercel['headers']) || isset($vercel['rewrites']))), 'vercel.json no mezcla routes con headers/rewrites (Vercel lo rechaza)');

echo "\nCV: " . ($total - $fallas) . " de $total pruebas bien\n";
exit($fallas ? 1 : 0);

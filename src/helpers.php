<?php
declare(strict_types=1);

/** Escapa texto para insertarlo en HTML. */
function e(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Devuelve el host de una URL, sin el prefijo www. */
function host(string $url): string
{
    $h = parse_url($url, PHP_URL_HOST) ?: $url;
    return preg_replace('/^www\./', '', $h);
}

/** Muestra una URL de GitHub en forma corta: usuario/repositorio. */
function short_repo(string $url): string
{
    $p = trim((string) parse_url($url, PHP_URL_PATH), '/');
    return $p !== '' ? 'github.com/' . $p : host($url);
}

/**
 * Genera los puntos de una traza de acelerograma para el encabezado.
 * Suma de senoides amortiguadas más ruido, con semilla fija para que
 * la figura sea idéntica en cada carga y en ambos idiomas.
 */
function trace_points(int $w = 1200, int $h = 150, int $n = 460, int $seed = 20260902): string
{
    mt_srand($seed);
    $mid  = $h / 2;
    $pts  = [];

    // Ruido suavizado, precalculado para dar textura a la señal.
    $noise = [];
    for ($i = 0; $i < $n; $i++) {
        $noise[$i] = mt_rand(-1000, 1000) / 1000;
    }
    for ($pass = 0; $pass < 2; $pass++) {
        $prev = $noise;
        for ($i = 1; $i < $n - 1; $i++) {
            $noise[$i] = ($prev[$i - 1] + $prev[$i] + $prev[$i + 1]) / 3;
        }
    }

    $raw = [];
    for ($i = 0; $i < $n; $i++) {
        $t = $i / ($n - 1);

        // Envolvente: llegada de la onda, pico y decaimiento.
        $env = $t < 0.16
            ? $t / 0.16 * 0.22
            : exp(-3.1 * ($t - 0.16)) * (1 - exp(-26 * ($t - 0.16))) + 0.10;

        $raw[$i] = $env * (
              0.62 * sin($t * 96.4)
            + 0.28 * sin($t * 233.7 + 1.1)
            + 0.16 * sin($t * 41.3 + 0.4)
            + 0.42 * $noise[$i]
        );
    }

    // Se normaliza al alto disponible para que la traza nunca desborde.
    $peak  = max(0.0001, max(array_map('abs', $raw)));
    $scale = ($h / 2 - 4) / $peak;

    for ($i = 0; $i < $n; $i++) {
        $pts[] = round($i / ($n - 1) * $w, 1) . ',' . round($mid - $raw[$i] * $scale, 1);
    }

    return implode(' ', $pts);
}

/**
 * Convierte una ruta interna en URL absoluta usando el host de la petición.
 * Necesario para canonical, hreflang y las etiquetas Open Graph.
 */
function site_url(string $path = '/'): string
{
    $host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? 'localhost';
    $host = preg_replace('/[^A-Za-z0-9\.\-:]/', '', $host);
    $proto = $_SERVER['HTTP_X_FORWARDED_PROTO']
        ?? ((($_SERVER['HTTPS'] ?? '') !== '' && ($_SERVER['HTTPS'] ?? 'off') !== 'off') ? 'https' : 'http');
    $proto = $proto === 'http' ? 'http' : 'https';

    return $proto . '://' . $host . '/' . ltrim($path, '/');
}

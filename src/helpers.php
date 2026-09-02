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

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

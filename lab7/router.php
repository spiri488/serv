<?php

declare(strict_types=1);

$basePath = '/lab7';

function startsWith(string $text, string $prefix): bool
{
    return substr($text, 0, strlen($prefix)) === $prefix;
}

function normalizePath(string $path, string $basePath): string
{
    if ($path === $basePath) {
        return '/';
    }

    if (startsWith($path, $basePath . '/')) {
        $path = substr($path, strlen($basePath));
    }

    return $path === '' ? '/' : $path;
}

function serveStaticFile(string $path, string $basePath): void
{
    $path = normalizePath($path, $basePath);

    $root = realpath(__DIR__);
    $file = realpath(__DIR__ . $path);

    if ($root === false || $file === false) {
        return;
    }

    if (!startsWith($file, $root) || !is_file($file)) {
        return;
    }

    $extension = pathinfo($file, PATHINFO_EXTENSION);

    $contentTypes = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
    ];

    if (isset($contentTypes[$extension])) {
        header('Content-Type: ' . $contentTypes[$extension]);
    }

    readfile($file);
    exit;
}

$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

serveStaticFile($requestPath, $basePath);

require __DIR__ . '/index.php';
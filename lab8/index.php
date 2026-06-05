<?php

declare(strict_types=1);

require __DIR__ . '/controllers/BlogController.php';

$basePath = '/lab8';

function routeStartsWith(string $text, string $prefix): bool
{
    return substr($text, 0, strlen($prefix)) === $prefix;
}

function getRoutePath(string $basePath): string
{
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

    if ($path === $basePath) {
        return '/';
    }

    if (routeStartsWith($path, $basePath . '/')) {
        $path = substr($path, strlen($basePath));
    }

    return $path === '' ? '/' : $path;
}

$path = getRoutePath($basePath);
$controller = new BlogController();

if ($path === '/') {
    $controller->index();
    exit;
}

if ($path === '/about-me') {
    $controller->aboutMe();
    exit;
}

if (preg_match('#^/hello/([^/]+)$#u', $path, $matches)) {
    $username = rawurldecode($matches[1]);
    $controller->sayHello($username);
    exit;
}

if (preg_match('#^/bye/([^/]+)$#u', $path, $matches)) {
    $name = rawurldecode($matches[1]);
    $controller->sayBye($name);
    exit;
}

$controller->notFound();
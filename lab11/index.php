<?php

declare(strict_types=1);

spl_autoload_register(function (string $className): void {
    $paths = [
        __DIR__ . '/src/Controllers/' . $className . '.php',
        __DIR__ . '/src/Database/' . $className . '.php',
        __DIR__ . '/src/Models/' . $className . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require $path;
            return;
        }
    }
});

$basePath = '/lab11';

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

$articlesController = new ArticlesController();
$commentsController = new CommentsController();

if ($path === '/' || $path === '/articles') {
    $articlesController->index();
    exit;
}

if (preg_match('#^/articles/(\d+)/comments$#', $path, $matches)) {
    $articleId = (int) $matches[1];
    $commentsController->store($articleId);
    exit;
}

if (preg_match('#^/comments/(\d+)/edit$#', $path, $matches)) {
    $commentId = (int) $matches[1];
    $commentsController->edit($commentId);
    exit;
}

if (preg_match('#^/articles/(\d+)$#', $path, $matches)) {
    $articleId = (int) $matches[1];
    $articlesController->show($articleId);
    exit;
}

if (preg_match('#^/article/(\d+)/edit$#', $path, $matches)) {
    $articleId = (int) $matches[1];
    $articlesController->edit($articleId);
    exit;
}

$articlesController->notFound();
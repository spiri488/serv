<?php

declare(strict_types=1);

class ArticlesController
{
    public function index(): void
    {
        $articles = Article::findAll();

        $this->render('articles/index.php', [
            'articles' => $articles,
        ]);
    }

    public function show(int $id): void
    {
        $article = Article::findById($id);

        if ($article === null) {
            $this->notFound();
            return;
        }

        // Главное место по заданию:
        // после получения статьи получаем автора из таблицы users
        $author = User::findById($article->authorId);

        $this->render('articles/show.php', [
            'article' => $article,
            'author' => $author,
        ], $article->name);
    }

    public function notFound(): void
    {
        http_response_code(404);

        $this->render('articles/show.php', [
            'article' => null,
            'author' => null,
        ], '404');
    }

    private function render(string $view, array $params = [], ?string $title = null): void
    {
        extract($params);

        ob_start();
        require __DIR__ . '/../../views/' . $view;
        $content = ob_get_clean();

        require __DIR__ . '/../../main.php';
    }
}
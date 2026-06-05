<?php

declare(strict_types=1);

class BlogController
{
    public function index(): void
    {
        $content = '
            <h2>Статья 1</h2>
            <p>Текст первой статьи</p>
            <hr>

            <h2>Статья 2</h2>
            <p>Текст второй статьи</p>
        ';

        $this->render('Главная страница', $content);
    }

    public function aboutMe(): void
    {
        $content = '
            <h2>Обо мне</h2>
            <p>Привет! Это страница с краткой информацией обо мне.</p>
            <p>Текст обо мне</p>
        ';

        $this->render('Обо мне', $content);
    }

    public function sayBye(string $name): void
    {
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

        $content = '
            <h2>Страница прощания</h2>
            <p>Пока, ' . $safeName . '</p>
        ';

        $this->render('Пока, ' . $safeName, $content);
    }

    public function notFound(): void
    {
        http_response_code(404);

        $content = '
            <h2>404</h2>
            <p>Страница не найдена.</p>
        ';

        $this->render('404', $content);
    }

    private function render(string $title, string $content): void
    {
        require __DIR__ . '/../main.php';
    }
}
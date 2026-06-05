<?php

declare(strict_types=1);

class BlogController
{
    // Метод для Главной страницы (урл: /)
    public function index(): void
    {
        $content = '
            <h2>Статья 1</h2>
            <p>Текст первой статьи</p>
            <hr>

            <h2>Статья 2</h2>
            <p>Текст второй статьи</p>
        ';

        
        $this->render($content);
    }

    // Метод для страницы "Обо мне" (урл: /about-me)
    public function aboutMe(): void
    {
        $content = '
            <h2>Обо мне</h2>
            <p>Привет! Это страница с краткой информацией обо мне.</p>
            <p>Текст обо мне</p>
        ';

        // Здесь мы явно передаем кастомный заголовок 'Обо мне'
        $this->render($content, 'Обо мне');
    }

    // Метод для страницы приветствия (урл: /hello/username)
    public function sayHello(string $username): void
    {
        $safeUsername = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

        $content = '
            <h2>Страница приветствия</h2>
            <p>Привет, ' . $safeUsername . '!</p>
        ';

        
        $this->render($content, 'Страница приветствия');
    }

    // Метод для страницы прощания (урл: /bye/name)
    public function sayBye(string $name): void
    {
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

        $content = '
            <h2>Страница прощания</h2>
            <p>Пока, ' . $safeName . '</p>
        ';

        $this->render($content, 'Страница прощания');
    }

    // Метод для ошибки 404 (Страница не найдена)
    public function notFound(): void
    {
        http_response_code(404);

        $content = '
            <h2>404</h2>
            <p>Страница не найдена.</p>
        ';

        $this->render($content, '404');
    }

    /*
     * Метод для вывода (рендеринга) страницы.
     * Принимает основной $content страницы и необязательный $title.
     * `string $title = ''` — значение по умолчанию делает этот
     * аргумент необязательным, что позволяет вызывать метод как $this->render($content).
     */
    private function render(string $content, string $title = ''): void
    {
        // Подключаем наш HTML-шаблон. Внутри файла main.php будут автоматически
        // доступны переменные $content и $title, которые мы получили в параметрах метода render().
        require __DIR__ . '/../main.php';
    }
}
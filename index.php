<?php
// Определяем абсолютный путь к файлу .env в текущей папке проекта
$envPath = __DIR__ . '/.env';

// Проверяем, существует ли файл .env на сервере
if (file_exists($envPath)) {
    // Если файл есть, читаем его встроенной функцией parse_ini_file.
    $env = parse_ini_file($envPath);
} else {
    // Если файла нет, инициализируем пустой массив
    $env = [];
}

// Получаем имя студента из массива $env[cite: 1].
$studentName = $env['STUDENT_NAME'] ?? 'ФИО не указано';

// С помощью функции date() получаем текущую дату и время сервера
$currentDate = date("d.m.Y");
$currentTime = date("H:i:s");

// Создаем массив с вариантами приветственных сообщений
$messages = [
    "Hello, World!",
    "PHP генерирует этот текст динамически.",
    "Это первая лабораторная"
];

// Выбираем случайное сообщение
$randomMessage = $messages[array_rand($messages)];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hello, World! — Лабораторная работа</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            /* Мягкий пастельный серо-кремовый фон */
            background-color: #f4f3ef;
            color: #403f3d;
        }

        /* Шапка страницы: нежный пастельно-бирюзовый/мятный цвет */
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 40px;
            background-color: #d7ebd9; 
            border-bottom: 2px solid #b8d8be;
        }

        .logo-img {
            max-height: 50px; /* Ограничиваем высоту логотипа, чтобы он не ломал шапку */
            display: block;
        }

        /* Название работы: глубокий хвойный цвет для хорошего контраста */
        .title {
            flex: 1;
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            color: #2c4a3e;
        }

        main {
            min-height: calc(100vh - 160px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        /* Белая карточка с мягкой пастельной тенью */
        .card {
            max-width: 600px;
            width: 100%;
            padding: 32px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(184, 216, 190, 0.25);
            text-align: center;
        }

        .card h1 {
            margin-top: 0;
            font-size: 36px;
            color: #403f3d;
        }

        /* Главный акцент: приятный пастельно-персиковый / коралловый цвет */
        .message {
            font-size: 22px;
            margin: 24px 0;
            color: #e28743;
            font-weight: 700;
        }

        .info {
            line-height: 1.7;
            color: #666562;
        }

        /* Подвал: приглушенный пастельно-каменный оттенок */
        footer {
            padding: 20px;
            text-align: center;
            background-color: #cccbc6;
            color: #403f3d;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <header>
        <img class="logo-img" src="logo.png" alt="Логотип МосПолитеха">
        <div class="title">Домашняя работа: Hello, World!</div>
        <div></div>
    </header>

    <main>
        <section class="card">
            <h1>Hello, World!</h1>

            <div class="message">
                <?= $randomMessage ?>
            </div>

            <div class="info">
                <p>Студент: <?= $studentName ?></p>
                <p>Сегодня: <?= $currentDate ?></p>
                <p>Текущее время сервера: <?= $currentTime ?></p>
            </div>
        </section>
    </main>

    <footer>
        задание для самостоятельной работы
    </footer>
</body>
</html>
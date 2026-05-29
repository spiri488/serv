<?php
// URL, у которого мы будем запрашивать заголовки ответа сервера
$url = 'https://httpbin.org/get';

// Встроенная PHP функция get_headers() отправляет HTTP-запрос и возвращает массив заголовков
$headers = get_headers($url);

if ($headers === false) {
    $headersOutput = 'Не удалось получить заголовки.';
} else {
    // Функция print_r с флагом true не выводит текст сразу, а возвращает его в виде строки,
    // чтобы мы могли красиво отобразить этот массив внутри HTML-тега
    $headersOutput = print_r($headers, true);
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>get_headers</title>
    <link rel="stylesheet" href="./style.css?v=2">
</head>
<body>
    <header class="header">
        <img src="logo.png" alt="logo" class="logo">
        <div class="title">Результат работы функции get_headers</div>
        <div class="header-spacer"></div>
    </header>

    <main class="main">
        <section class="card">
            <h1>Заголовки ответа сервера</h1>

            <p class="description">
                URL для проверки: <?= htmlspecialchars($url) ?>
            </p>

            <textarea class="headers-result" readonly><?= htmlspecialchars($headersOutput) ?></textarea>

            <a class="link-button" href="./index.php">Вернуться к форме</a>
        </section>
    </main>

    <footer class="footer">
        задание для самостоятельно работы
    </footer>
</body>
</html>
<?php

$pageTitle = $title ?? 'Мой блог';

if (trim($pageTitle) === '') {
    $pageTitle = 'Мой блог';
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/styles/styles.css">
</head>
<body>

<table class="layout">
    <tr>
        <td colspan="2" class="header">
            <img class="logo" src="/logo.png" alt="logo">
            <span class="blog-title">Мой блог</span>
            <span class="header-spacer"></span>
        </td>
    </tr>

    <tr>
        <td>
            <?= $content ?>
        </td>

        <td width="300px" class="sidebar">
            <div class="sidebarHeader">Меню</div>
            <ul>
                <li><a href="/">Главная страница</a></li>
                <li><a href="/articles">Статьи</a></li>
                <li><a href="/articles/1">Статья №1</a></li>
                <li><a href="/articles/2">Статья №2</a></li>
            </ul>
        </td>
    </tr>

    <tr>
        <td class="footer" colspan="2">Все права защищены (c) Мой блог</td>
    </tr>
</table>

</body>
</html>
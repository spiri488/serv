<?php
// Задаем уравнение (код сработает и для '22 * X = 220', и для 'X * 22 = 220')
$equation = '22 * X = 220';

// 1. Инициализируем переменные по умолчанию
$operator = 'Не определен';
$position = 'Не определена';
$number = 0;
$result = 0;
$x = 0;
$solutionSteps = '';

// Убираем все лишние пробелы из уравнения для корректного анализа
$cleanEquation = str_replace(' ', '', $equation);

// 2. Разделяем уравнение по знаку равенства '=' на левую и правую части
$parts = explode('=', $cleanEquation);
$leftPart = $parts[0];  // Всё, что до '=' (например, '22*X')
$rightPart = $parts[1]; // Всё, что после '=' (например, '220')

// Результат уравнения — это всегда число в правой части
$result = floatval($rightPart);

// 3. Массив возможных операторов
$possibleOperators = ['+', '-', '*', '/'];

// Ищем, какой именно оператор используется в левой части
foreach ($possibleOperators as $op) {
    if (strpos($leftPart, $op) !== false) {
        $operator = $op;
        break;
    }
}

// 4. Разделяем левую часть по найденному оператору, чтобы найти число и позицию X
if ($operator !== 'Не определен') {
    $leftElements = explode($operator, $leftPart);
    $leftElement = $leftElements[0];  // Элемент слева от оператора
    $rightElement = $leftElements[1]; // Элемент справа от оператора

    // Определяем, где находится X, находим известное число и считаем ответ
    if (strtoupper($leftElement) === 'X') {
        $position = 'X находится слева от оператора';
        $number = floatval($rightElement);

        // Логика вычислений в зависимости от оператора, если X на первом месте
        switch ($operator) {
            case '+': $x = $result - $number; $solutionSteps = "X = {$result} - {$number}"; break;
            case '-': $x = $result + $number; $solutionSteps = "X = {$result} + {$number}"; break;
            case '*': $x = $result / $number; $solutionSteps = "X = {$result} / {$number}"; break;
            case '/': $x = $result * $number; $solutionSteps = "X = {$result} * {$number}"; break;
        }
    } else {
        $position = 'X находится справа от оператора';
        $number = floatval($leftElement);

        // Логика вычислений в зависимости от оператора, если X на втором месте
        switch ($operator) {
            case '+': $x = $result - $number; $solutionSteps = "X = {$result} - {$number}"; break;
            case '-': $x = $number - $result; $solutionSteps = "X = {$number} - {$result}"; break;
            case '*': $x = $result / $number; $solutionSteps = "X = {$result} / {$number}"; break;
            case '/': $x = $number / $result; $solutionSteps = "X = {$number} / {$result}"; break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solve the equation dynamically</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <header class="header">
        <img class="logo" src="logo.png" alt="logo">
        <div class="title">Домашняя работа: Solve the equation</div>
        <div class="header-spacer"></div>
    </header>

    <main class="main">
        <section class="card">
            <h1>Автоматическое решение уравнения</h1>

            <div class="equation">
                <?= htmlspecialchars($equation) ?>
            </div>

            <div class="result">
                <p>
                    <strong>Обнаруженный оператор:</strong> 
                    <span class="badge"><?= htmlspecialchars($operator) ?></span>
                </p>

                <p>
                    <strong>Расположение переменной X:</strong> <br>
                    <?= htmlspecialchars($position) ?>
                </p>

                <p>
                    <strong>Математическое решение:</strong> <br>
                    <code><?= htmlspecialchars($solutionSteps) ?></code>
                </p>

                <p class="answer">
                    X = <?= $x ?>
                </p>
            </div>
        </section>
    </main>

    <footer class="footer">
        задание для самостоятельной работы
    </footer>
</body>
</html>
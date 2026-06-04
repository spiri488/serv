<?php
/**
 * ============================================================================
 * БЛОК БАЗОВЫХ МАТЕМАТИЧЕСКИХ ФУНКЦИЙ С ОБРАБОТКОЙ ИСКЛЮЧИТЕЛЬНЫХ СИТУАЦИЙ
 * ============================================================================
 */

function addNumbers(float $a, float $b): float
{
    return $a + $b;
}

function subtractNumbers(float $a, float $b): float
{
    return $a - $b;
}

function multiplyNumbers(float $a, float $b): float
{
    return $a * $b;
}

/**
 * Деление чисел с защитой от деления на ноль (с учетом погрешности float)
 */
function divideNumbers(float $a, float $b): float
{
    // Так как работаем с типами float, напрямую с нулем не сравниваем из-за погрешности.
    // Проверяем, близко ли число к нулю по модулю меньше чем на epsilon (1e-7).
    if (abs($b) < 0.0000001) {
        throw new Exception('Деление на ноль невозможно');
    }
    return $a / $b;
}

/**
 * Возведение в степень с валидацией результата
 */
function powerNumbers(float $a, float $b): float
{
    $result = $a ** $b;

    // Проверяем, является ли результат конечным числом (не INF и не NaN)
    if (!is_finite($result)) {
        throw new Exception('Некорректное возведение в степень');
    }
    return $result;
}

/**
 * Извлечение квадратного корня
 */
function squareRootNumber(float $number): float
{
    if ($number < 0) {
        throw new Exception('Корень из отрицательного числа невозможен');
    }
    return sqrt($number);
}

/**
 * Вычисление натурального логарифма (по основанию e)
 */
function naturalLogNumber(float $number): float
{
    if ($number <= 0) {
        throw new Exception('ln можно вычислить только для положительного числа');
    }
    return log($number);
}

/**
 * Вычисление десятичного логарифма (по основанию 10)
 */
function decimalLogNumber(float $number): float
{
    if ($number <= 0) {
        throw new Exception('log можно вычислить только для положительного числа');
    }
    return log10($number);
}

/**
 * Вычисление факториала числа с проверками на корректность входных данных
 */
function factorialNumber(float $number): float
{
    if ($number < 0) {
        throw new Exception('Факториал отрицательного числа невозможен');
    }

    // Проверка: является ли число целым (с учетом погрешности float чисел)
    if (abs($number - round($number)) > 0.0000001) {
        throw new Exception('Факториал можно вычислить только для целого числа');
    }

    // Ограничение сверху, так как 171! превышает максимальное значение типа float (около 1.8e308)
    if ($number > 170) {
        throw new Exception('Слишком большое число для факториала');
    }

    $result = 1;
    // Цикл вычисления факториала для приведенного к int значения
    for ($i = 2; $i <= (int)$number; $i++) {
        $result *= $i;
    }
    return $result;
}

/**
 * Функция красивого форматирования итогового результата для вывода на экран
 */
function formatResult(float $number): string
{
    // Если число является целым (или крайне близко к нему), возвращаем его как целое
    if (abs($number - round($number)) < 0.0000001) {
        return (string)round($number);
    }

    // Выводим число с 10 знаками после запятой, убирая лишние концевые нули и точку справа
    return rtrim(rtrim(number_format($number, 10, '.', ''), '0'), '.');
}


/**
 * ============================================================================
 * КЛАСС СИНТАКСИЧЕСКОГО АНАЛИЗА (ПАРСЕР ВЫРАЖЕНИЙ МЕТОДОМ РЕКУРСИВНОГО СНИСХОЖДЕНИЯ)
 * Грамматика:
 * Expression -> Term [ ('+'|'-') Term ]*
 * Term       -> Power [ ('*'|'/') Power ]*
 * Power      -> Unary [ '^' Power ]
 * Unary      -> ['+'|'-'] Unary | Postfix
 * Postfix    -> Primary [ '!' ]*
 * Primary    -> Number | '(' Expression ')' | Function'(' Expression ')' | Constant
 * ============================================================================
 */
class CalculatorParser
{
    private string $expression = ''; // Хранилище обрабатываемой строки выражения
    private int $position = 0;       // Текущий индекс (указатель) символа в строке

    /**
     * Главный публичный метод для запуска парсинга и вычисления выражения
     */
    public function calculate(string $expression): float
    {
        // Первичная санитизация: перевод в нижний регистр, удаление пробелов, замена запятых на точки
        $this->expression = strtolower(trim($expression));
        $this->expression = str_replace(',', '.', $this->expression);
        $this->expression = preg_replace('/\s+/', '', $this->expression);
        $this->position = 0; // Сброс указателя считывания в начало

        if ($this->expression === '') {
            throw new Exception('Введите выражение');
        }

        // Белый список допустимых символов (защита от инъекций кода и некорректного ввода)
        if (!preg_match('/^[0-9+\-*\/^().!a-z]+$/', $this->expression)) {
            throw new Exception('Выражение содержит недопустимые символы');
        }

        // Запуск рекурсивного разбора с самого верхнего уровня грамматики (Выражение)
        $result = $this->parseExpression();

        // Если после разбора указатель дошел не до конца строки, значит в выражении синтаксическая ошибка
        if ($this->position !== strlen($this->expression)) {
            throw new Exception('Некорректное выражение');
        }

        // Проверка финального результата на бесконечность/нечисло
        if (!is_finite($result)) {
            throw new Exception('Результат невозможно вычислить');
        }

        return $result;
    }

    /**
     * Разбор математического выражения (Сложение и Вычитание)
     */
    private function parseExpression(): float
    {
        $result = $this->parseTerm(); // Сначала разбираем приоритетное умножение/деление

        while (!$this->isEnd()) {
            if ($this->match('+')) {
                $result = addNumbers($result, $this->parseTerm());
            } elseif ($this->match('-')) {
                $result = subtractNumbers($result, $this->parseTerm());
            } else {
                break; // Если символы не + и не -, выходим из цикла сложения
            }
        }
        return $result;
    }

    /**
     * Разбор слагаемых/компонентов выражения (Умножение и Деление)
     */
    private function parseTerm(): float
    {
        $result = $this->parsePower(); // Сначала разбираем приоритетное возведение в степень

        while (!$this->isEnd()) {
            if ($this->match('*')) {
                $result = multiplyNumbers($result, $this->parsePower());
            } elseif ($this->match('/')) {
                $result = divideNumbers($result, $this->parsePower());
            } else {
                break;
            }
        }
        return $result;
    }

    /**
     * Разбор операции возведения в степень (Оператор '^')
     */
    private function parsePower(): float
    {
        $result = $this->parseUnary(); // Разбираем унарные знаки/функции

        if ($this->match('^')) {
            // Рекурсивный вызов parsePower обеспечивает правостороннюю ассоциативность (например, 2^3^2 = 2^(3^2))
            $result = powerNumbers($result, $this->parsePower());
        }
        return $result;
    }

    /**
     * Разбор унарных операторов (Унарный плюс '+' и Унарный минус '-')
     */
    private function parseUnary(): float
    {
        if ($this->match('+')) {
            return $this->parseUnary(); // Унарный плюс ничего не меняет, идем глубже
        }

        if ($this->match('-')) {
            return -$this->parseUnary(); // Унарный минус инвертирует знак подвыражения
        }

        return $this->parsePostfix();
    }

    /**
     * Разбор постфиксных операций (Факториал '!')
     */
    private function parsePostfix(): float
    {
        $result = $this->parsePrimary(); // Разбираем базовые элементы (числа, скобки, константы)

        // Поддержка цепочки факториалов (например, 3!! = (3!)!)
        while ($this->match('!')) {
            $result = factorialNumber($result);
        }
        return $result;
    }

    /**
     * Разбор базовых неделимых элементов: чисел, констант, скобок, функций
     */
    private function parsePrimary(): float
    {
        // 1. Обработка выражений внутри круглых скобок
        if ($this->match('(')) {
            $result = $this->parseExpression(); // Рекурсивно вычисляем всё внутреннее выражение

            if (!$this->match(')')) {
                throw new Exception('Не закрыта скобка');
            }
            return $result;
        }

        // 2. Проверка и парсинг математических функций
        if ($this->startsWith('sqrt')) {
            return $this->parseFunction('sqrt');
        }
        if ($this->startsWith('ln')) {
            return $this->parseFunction('ln');
        }
        if ($this->startsWith('log')) {
            return $this->parseFunction('log');
        }

        // 3. Обработка константы Пи
        if ($this->startsWith('pi')) {
            $this->position += 2; // Сдвигаем указатель на длину слова "pi"
            return pi();
        }

        // 4. Обработка константы Экспоненты
        if ($this->startsWith('e')) {
            $this->position += 1; // Сдвигаем указатель на длину "e"
            return exp(1);
        }

        // 5. Если это не скобка, не функция и не константа — парсим как обычное число
        return $this->parseNumber();
    }

    /**
     * Вспомогательный метод парсинга аргументов встроенных функций
     */
    private function parseFunction(string $functionName): float
    {
        $this->position += strlen($functionName); // Пропускаем имя функции в строке

        if (!$this->match('(')) {
            throw new Exception('После функции должна быть открывающая скобка');
        }

        $value = $this->parseExpression(); // Вычисляем выражение внутри скобок функции

        if (!$this->match(')')) {
            throw new Exception('После аргумента функции должна быть закрывающая скобка');
        }

        // Перенаправляем аргумент в соответствующую математическую функцию
        if ($functionName === 'sqrt') {
            return squareRootNumber($value);
        }
        if ($functionName === 'ln') {
            return naturalLogNumber($value);
        }
        if ($functionName === 'log') {
            return decimalLogNumber($value);
        }

        throw new Exception('Неизвестная функция');
    }

    /**
     * Метод извлечения и парсинга числовых значений из строки выражения
     */
    private function parseNumber(): float
    {
        $number = '';
        $hasDigit = false;
        $hasDot = false;

        // Посимвольно считываем цифры и одну возможную десятичную точку
        while (!$this->isEnd()) {
            $char = $this->current();

            if (ctype_digit($char)) {
                $number .= $char;
                $hasDigit = true;
                $this->position++;
                continue;
            }

            if ($char === '.' && !$hasDot) {
                $number .= $char;
                $hasDot = true;
                $this->position++;
                continue;
            }

            break; // Встретили не цифру и не точку — чтение числа завершено
        }

        if (!$hasDigit) {
            throw new Exception('Ожидалось число');
        }

        return (float)$number; // Приведение собранной строки к типу float
    }

    /**
     * Метод проверки: совпадает ли текущий символ с ожидаемым. Если да — сдвигает указатель.
     */
    private function match(string $expected): bool
    {
        if ($this->current() === $expected) {
            $this->position++;
            return true;
        }
        return false;
    }

    /**
     * Проверка: начинается ли остаток строки с определенной подстроки (для функций/констант)
     */
    private function startsWith(string $value): bool
    {
        return substr($this->expression, $this->position, strlen($value)) === $value;
    }

    /**
     * Возвращает текущий символ строки без сдвига указателя
     */
    private function current(): ?string
    {
        if ($this->isEnd()) {
            return null;
        }
        return $this->expression[$this->position];
    }

    /**
     * Проверка: достигнут ли конец строки выражения
     */
    private function isEnd(): bool
    {
        return $this->position >= strlen($this->expression);
    }
}

/**
 * ============================================================================
 * ОБРАБОТКА HTTP-ЗАПРОСОВ (КОНТРОЛЛЕР)
 * Использование паттерна PRG (Post-Redirect-Get) для предотвращения повторной отправки формы
 * ============================================================================
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $expression = $_POST['expression'] ?? '';

    try {
        $parser = new CalculatorParser();
        $result = $parser->calculate($expression);

        // В случае успеха перенаправляем на этот же скрипт с GET-параметрами результата
        header(
            'Location: ./index.php?expression=' . urlencode($expression) .
            '&result=' . urlencode(formatResult($result))
        );
        exit;
    } catch (Exception $error) {
        // В случае ошибки перенаправляем на этот же скрипт с текстом ошибки
        header(
            'Location: ./index.php?expression=' . urlencode($expression) .
            '&error=' . urlencode($error->getMessage())
        );
        exit;
    }
}

// Прием параметров для отображения после редиректа (GET)
$expressionFromGet = $_GET['expression'] ?? '';
$resultFromGet = $_GET['result'] ?? '';
$errorFromGet = $_GET['error'] ?? '';

// Определяем, что вывести на экран калькулятора: результат (если есть) или введенное выражение
$displayValue = $resultFromGet !== '' ? $resultFromGet : $expressionFromGet;
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculator</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <header class="header">
        <img class="logo" src="logo.png" alt="logo">
        <div class="title">Домашняя работа: Calculator</div>
        <div class="header-spacer"></div>
    </header>

    <main class="main">
        <section class="calculator">
            <h1>Калькулятор</h1>

            <form action="./index.php" method="POST" class="calculator-form" id="calculatorForm">
                <input
                    class="display"
                    id="display"
                    type="text"
                    name="expression"
                    value="<?= htmlspecialchars($displayValue) ?>"
                    placeholder="Введите выражение"
                    autocomplete="off"
                >

                <?php if ($errorFromGet !== ''): ?>
                    <p class="error">
                        <?= htmlspecialchars($errorFromGet) ?>
                    </p>
                <?php endif; ?>

                <div class="buttons">
                    <button type="button" id="clear">C</button>
                    <button type="button" id="backspace">←</button>
                    <button type="button" data-value="(">(</button>
                    <button type="button" data-value=")">)</button>

                    <button type="button" data-value="sqrt(">√</button>
                    <button type="button" data-value="ln(">ln</button>
                    <button type="button" data-value="log(">log</button>
                    <button type="button" data-value="!">!</button>

                    <button type="button" data-value="pi">π</button>
                    <button type="button" data-value="e">e</button>
                    <button type="button" data-value="^">^</button>
                    <button type="button" data-value="/">/</button>

                    <button type="button" data-value="7">7</button>
                    <button type="button" data-value="8">8</button>
                    <button type="button" data-value="9">9</button>
                    <button type="button" data-value="*">*</button>

                    <button type="button" data-value="4">4</button>
                    <button type="button" data-value="5">5</button>
                    <button type="button" data-value="6">6</button>
                    <button type="button" data-value="-">-</button>

                    <button type="button" data-value="1">1</button>
                    <button type="button" data-value="2">2</button>
                    <button type="button" data-value="3">3</button>
                    <button type="button" data-value="+">+</button>

                    <button type="button" data-value="0">0</button>
                    <button type="button" data-value=".">.</button>
                    <button type="submit" class="equals">=</button>
                </div>
            </form>

            <div class="help">
                <p>Примеры: <code>sqrt(9)</code>, <code>2^3</code>, <code>5!</code>, <code>ln(e)</code>, <code>log(100)</code>, <code>-(2+3)</code></p>
            </div>
        </section>
    </main>

    <footer class="footer">
        задание для самостоятельно работы
    </footer>

    <script src="./script.js"></script>
</body>
</html>
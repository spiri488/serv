<?php

declare(strict_types=1);

// Объявляем интерфейс. Он описывает только возможности/способности, которыми должен обладать класс.
interface CalculateSquare
{
    // Любой класс, реализующий этот интерфейс, обязан иметь метод вычисления площади, возвращающий float.
    public function calculateSquare(): float;
}

// Класс "Круг" обязуется выполнять контракт CalculateSquare
class Circle implements CalculateSquare
{
    private float $radius;

    public function __construct(float $radius)
    {
        $this->radius = $radius;
    }

    // Реализация контракта для круга (пи * r^2)
    public function calculateSquare(): float
    {
        return pi() * $this->radius * $this->radius;
    }
}

// Класс "Прямоугольник" также обязуется выполнять контракт CalculateSquare
class Rectangle implements CalculateSquare
{
    private float $width;
    private float $height;

    public function __construct(float $width, float $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    // Реализация контракта для прямоугольника (ширина * высота)
    public function calculateSquare(): float
    {
        return $this->width * $this->height;
    }
}

// Сторонний класс "Пользователь", у которого нет и не должно быть вычисления площади
class User
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}

// Функция для вывода информации о площади. Принимает универсальный тип object.
function printSquareInfo(object $object): void
{
    // Получаем имя класса переданного объекта (например, "Circle" или "User")
    $className = get_class($object);

    // Проверка полиморфизма: проверяем, подписан ли объект на нужный интерфейс
    if ($object instanceof CalculateSquare) {
        echo 'Объект класса ' . $className . ' реализует интерфейс CalculateSquare.' . PHP_EOL;
        // Мы уверены, что метод существует, так как класс реализует интерфейс
        echo 'Площадь: ' . round($object->calculateSquare(), 2) . PHP_EOL;
        return;
    }

    // Если объект не реализует интерфейс (как класс User)
    echo 'Объект класса ' . $className . ' не реализует интерфейс CalculateSquare.' . PHP_EOL;
}

// Создаем массив из абсолютно разнородных объектов
$objects = [
    new Circle(5),
    new Rectangle(4, 6),
    new User('Иван'),
];

// Проходим циклом по всем объектам и отправляем их в функцию проверки
foreach ($objects as $object) {
    printSquareInfo($object);
    echo PHP_EOL;
}
<?php

// Включаем строгую типизацию. PHP будет выдавать ошибку, если типы аргументов не совпадают с объявленными.
declare(strict_types=1);

class Cat
{
    // Скрытые свойства (доступны только внутри самого класса Cat)
    private string $name;
    private string $color;

    // Конструктор — вызывается автоматически при создании объекта через `new Cat(...)`
    public function __construct(string $name, string $color)
    {
        // Инициализируем приватные свойства переданными значениями
        $this->name = $name;
        $this->color = $color;
    }

    // Геттер для получения имени кота из внешнего кода
    public function getName(): string
    {
        return $this->name;
    }

    // Геттер для получения цвета кота из внешнего кода
    public function getColor(): string
    {
        return $this->color;
    }

    // Публичный метод, описывающий поведение объекта
    public function sayHello(): string
    {
        // Внутри класса мы можем обращаться к свойствам как напрямую ($this->name), 
        // так и через геттеры ($this->getName()). Использование геттеров считается более гибким подходом.
        return 'Мяу! Меня зовут ' . $this->getName() . '. Я ' . $this->getColor() . ' цвета.';
    }
}

// Создаем экземпляр (объект) класса Cat, передавая параметры в конструктор
$cat = new Cat('Сеня', 'белого');

// Вызываем публичный метод и выводим результат в консоль/браузер с переносом строки (PHP_EOL)
echo $cat->sayHello() . PHP_EOL;
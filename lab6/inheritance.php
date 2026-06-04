<?php

declare(strict_types=1);

// Базовый (родительский) класс, описывающий абстрактный урок
class Lesson
{
    // Приватные свойства — дочерние классы не имеют к ним прямого доступа!
    private string $title;
    private string $text;
    private string $homework;

    // Конструктор родительского класса
    public function __construct(string $title, string $text, string $homework)
    {
        $this->title = $title;
        $this->text = $text;
        $this->homework = $homework;
    }

    // Геттеры и сеттеры для управления приватными свойствами родителя
    public function getTitle(): string { return $this->title; }
    public function setTitle(string $title): void { $this->title = $title; }

    public function getText(): string { return $this->text; }
    public function setText(string $text): void { $this->text = $text; }

    public function getHomework(): string { return $this->homework; }
    public function setHomework(string $homework): void { $this->homework = $homework; }
}

// Дочерний класс, который наследует класс Lesson и расширяет его функционалом оплаты
class PaidLesson extends Lesson
{
    // Новое свойство, специфичное только для платного урока
    private float $price;

    // Конструктор дочернего класса
    public function __construct(string $title, string $text, string $homework, float $price)
    {
        // Вызываем конструктор родительского класса (Lesson), чтобы заполнить свойства title, text и homework.
        // Без этого вызова базовые свойства объекта останутся неинициализированными.
        parent::__construct($title, $text, $homework);
        
        // Инициализируем собственное свойство класса PaidLesson
        $this->price = $price;
    }

    // Собственные геттер и сеттер для цены
    public function getPrice(): float { return $this->price; }
    public function setPrice(float $price): void { $this->price = $price; }
}

// Создаем объект дочернего класса. Передаем данные как для родителя, так и для самого PaidLesson.
$lesson = new PaidLesson(
    'Урок о наследовании в PHP',
    'Лол, кек, чебурек',
    'Ложитесь спать, утро вечера мудренее',
    99.90
);

// var_dump покажет структуру объекта: он содержит в себе и свойства Lesson, и свойство PaidLesson.
var_dump($lesson);
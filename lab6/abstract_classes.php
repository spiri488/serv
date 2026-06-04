<?php

declare(strict_types=1);

// Абстрактный класс — служит только фундаментом для наследования
abstract class HumanAbstract
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    // Абстрактные методы. У них нет тела, они лишь задают контракт: 
    // "Каждый, кто считает себя Человеком, обязан уметь приветствовать и говорить 'меня зовут' на своем языке".
    abstract public function getGreetings(): string;
    abstract public function getMyNameIs(): string;

    // Обычный (конкретный) метод. Он использует абстрактные методы, 
    // которые будут написаны в будущем в дочерних классах. Это проявление полиморфизма.
    public function introduceYourself(): string
    {
        return $this->getGreetings() . '! ' . $this->getMyNameIs() . ' ' . $this->getName() . '.';
    }
}

// Конкретный класс для русскоговорящего человека
class RussianHuman extends HumanAbstract
{
    // Обязательная реализация абстрактного метода родителя
    public function getGreetings(): string
    {
        return 'Привет';
    }

    // Обязательная реализация абстрактного метода родителя
    public function getMyNameIs(): string
    {
        return 'Меня зовут';
    }
}

// Конкретный класс для англоговорящего человека
class EnglishHuman extends HumanAbstract
{
    // Обязательная реализация абстрактного метода родителя
    public function getGreetings(): string
    {
        return 'Hello';
    }

    // Обязательная реализация абстрактного метода родителя
    public function getMyNameIs(): string
    {
        return 'My name is';
    }
}

// Создавать объекты можно только от конкретных (не абстрактных) классов
$ivan = new RussianHuman('Илья');
$john = new EnglishHuman('Sam');

// Метод introduceYourself() вызовется из абстрактного класса, 
// но под капотом он динамически обратится к языковым методам соответствующих подклассов.
echo $ivan->introduceYourself() . PHP_EOL;
echo $john->introduceYourself() . PHP_EOL;
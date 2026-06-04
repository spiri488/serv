/**
 * ============================================================================
 * СКРИПТ ИНТЕРАКТИВНОГО ИНТЕРФЕЙСА КАЛЬКУЛЯТОРА
 * Управляет поведением кнопок и вводом символов в поле без потери позиции курсора.
 * ============================================================================
 */

// Поиск ключевых DOM-элементов на странице
const display = document.querySelector('#display');
const clearButton = document.querySelector('#clear');
const backspaceButton = document.querySelector('#backspace');
const valueButtons = document.querySelectorAll('[data-value]'); // Кнопки, имеющие атрибут data-value

/**
 * Вставка символа/функции в текущую позицию курсора (каретки).
 * Позволяет добавлять символы не только в конец, но и в середину уже написанного текста.
 */
function insertValue(value) {
    const start = display.selectionStart; // Позиция начала выделения (или просто курсора)
    const end = display.selectionEnd;     // Позиция конца выделения

    // Формируем новую строку: текст ДО курсора + вставляемое значение + текст ПОСЛЕ курсора
    display.value =
        display.value.slice(0, start) +
        value +
        display.value.slice(end);

    // Вычисляем новую позицию курсора (сдвигается на длину вставленного куска)
    const newPosition = start + value.length;

    display.focus(); // Возвращаем фокус на поле ввода
    display.setSelectionRange(newPosition, newPosition); // Устанавливаем курсор на новое место
}

// Навешиваем обработчик клика на все кнопки, которые вводят данные (цифры, знаки, скобки)
valueButtons.forEach((button) => {
    button.addEventListener('click', () => {
        // Передаем значение из HTML-атрибута data-value в функцию вставки
        insertValue(button.dataset.value);
    });
});

// Логика кнопки "C" (Полная очистка поля ввода)
clearButton.addEventListener('click', () => {
    display.value = ''; // Стираем весь текст
    display.focus();    // Возвращаем фокус
});

// Логика кнопки "←" (Backspace / Удаление одного символа)
backspaceButton.addEventListener('click', () => {
    const start = display.selectionStart;
    const end = display.selectionEnd;

    // Сценарий 1: Пользователь выделил мышкой кусок текста и нажал Backspace
    if (start !== end) {
        // Вырезаем выделенный кусок из строки
        display.value = display.value.slice(0, start) + display.value.slice(end);
        display.setSelectionRange(start, start); // Оставляем курсор в точке удаления
    } 
    // Сценарий 2: Выделения нет, курсор стоит в конкретном месте и слева есть символ
    else if (start > 0) {
        // Удаляем один символ, стоящий ровно перед курсором (start - 1)
        display.value = display.value.slice(0, start - 1) + display.value.slice(start);
        display.setSelectionRange(start - 1, start - 1); // Сдвигаем курсор на 1 символ назад
    }

    display.focus(); // Сохраняем фокус на поле
});

// Горячие клавиши: Очистка поля при нажатии на клавишу Escape
display.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        display.value = '';
    }
});
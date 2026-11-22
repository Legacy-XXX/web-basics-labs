'use strict';

// Допоміжні функції: вивід у консоль і дублювання на сторінку
const output = document.getElementById('output');

function log(...args) {
  console.log(...args);
  const line = args
    .map((a) => (typeof a === 'object' && a !== null ? JSON.stringify(a) : String(a)))
    .join(' ');
  output.textContent += line + '\n';
}

function section(title) {
  console.log(`%c${title}`, 'color:#1f5ad6;font-weight:bold;font-size:13px');
  output.textContent += `\n=== ${title} ===\n`;
}

// 1. IIFE — функція, що викликається одразу після оголошення
section('1. IIFE');
(function () {
  const language = window.clientInformation?.language ?? navigator.language;
  log('Мова браузера (window.clientInformation.language):', language);
})();

// 2. Методи масивів: map, filter, reduce
section('2. map, filter, reduce');
const numbers = [12, 5, 27, 33, 48, 19, 64, 7, 21, 90, 36];
log('Початковий масив:', numbers);

const doubled = numbers.map((n) => n * 2);
log('map — кожне число подвоєне:', doubled);

const greaterThan40 = numbers.filter((n) => n > 40);
log('filter — числа більше 40:', greaterThan40);

const sum = numbers.reduce((acc, n) => acc + n, 0);
log('reduce — сума всіх елементів:', sum);

// 3. Rest Parameters
section('3. Rest Parameters: sumAll');
function sumAll(...values) {
  return values.reduce((acc, n) => acc + n, 0);
}
log('sumAll(1, 2, 3) =', sumAll(1, 2, 3));
log('sumAll(10, 20, 30, 40, 50) =', sumAll(10, 20, 30, 40, 50));
log('sumAll() =', sumAll());
log('sumAll(...numbers) =', sumAll(...numbers));

// 4. Робота з Map
section('4. Map: студенти та бали');
const students = new Map([
  ['Олена Коваль', 95],
  ['Андрій Мельник', 82],
  ['Ірина Бондар', 74],
]);
students.set('Максим Ткаченко', 88);
students.set('Софія Шевчук', 91);

for (const [name, score] of students) {
  log(`${name} — ${score}`);
}
log('Кількість студентів (size):', students.size);
log('Бал Ірини Бондар (get):', students.get('Ірина Бондар'));
log('Чи є «Петро Іваненко» (has):', students.has('Петро Іваненко'));

// 5. Зміна контексту функції: call та apply
section('5. call та apply');
const person = { firstName: 'Olena', lastName: 'Koval' };
const student = { firstName: 'Andrii', lastName: 'Melnyk' };
const guest = { firstName: 'John', lastName: 'Smith' };

function greet(greeting = 'Hello', mark = '!') {
  log(`${greeting}, ${this.firstName} ${this.lastName}${mark}`);
}

greet.call(person);
greet.call(student);
greet.apply(guest);
greet.apply(person, ['Hi', '!!!']);

// 6. Замикання (closure)
section('6. Замикання: createCounterWithStart');
function createCounterWithStart(start) {
  let count = start;
  return function () {
    count += 1;
    log('Лічильник:', count);
    return count;
  };
}

const counterFrom10 = createCounterWithStart(10);
counterFrom10(); // 11
counterFrom10(); // 12
counterFrom10(); // 13

const counterFrom100 = createCounterWithStart(100);
counterFrom100(); // 101
counterFrom10(); // 14 — у кожного лічильника власне значення

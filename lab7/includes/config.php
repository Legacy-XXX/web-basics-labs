<?php
declare(strict_types=1);

// Параметри підключення до MySQL
const DB_HOST = 'localhost';
const DB_NAME = 'klats_lab';
const DB_USER = 'klats';
const DB_PASS = 'klats_pass_2026';

// Категорії товарів: ключ => [назва, зображення]
const CATEGORIES = [
    'mouse' => ['Миші', 'assets/img/mouse.svg'],
    'keyboard' => ['Клавіатури', 'assets/img/keyboard.svg'],
    'headphones' => ['Навушники', 'assets/img/headphones.svg'],
    'monitor' => ['Монітори', 'assets/img/monitor.svg'],
    'mousepad' => ['Килимки', 'assets/img/mousepad.svg'],
    'webcam' => ['Вебкамери', 'assets/img/webcam.svg'],
];

// Поля, за якими дозволено сортування: параметр URL => [стовпець БД, підпис]
const SORT_FIELDS = [
    'created_at' => ['created_at', 'Дата додавання'],
    'product' => ['product', 'Назва товару'],
    'price' => ['price', 'Ціна'],
    'customer_name' => ['customer_name', 'Покупець'],
];

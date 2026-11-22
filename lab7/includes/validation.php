<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

// Екранування для виводу в HTML (захист від XSS)
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// Довжина рядка в символах UTF-8
function textLength(string $value): int
{
    return preg_match_all('/./us', $value);
}

// Зчитування й перевірка даних форми; повертає [очищені дані, помилки]
function validateOrder(array $input): array
{
    $data = [
        'customer_name' => trim((string) ($input['customer_name'] ?? '')),
        'product' => trim((string) ($input['product'] ?? '')),
        'category' => trim((string) ($input['category'] ?? '')),
        'quantity' => trim((string) ($input['quantity'] ?? '')),
        'price' => str_replace(',', '.', trim((string) ($input['price'] ?? ''))),
        'email' => trim((string) ($input['email'] ?? '')),
        'phone' => preg_replace('/[\s()\-]/', '', (string) ($input['phone'] ?? '')),
        'comment' => trim((string) ($input['comment'] ?? '')),
    ];
    $errors = [];

    if ($data['customer_name'] === '') {
        $errors['customer_name'] = "Вкажіть ім'я покупця.";
    } elseif (textLength($data['customer_name']) < 2 || textLength($data['customer_name']) > 100) {
        $errors['customer_name'] = "Ім'я має містити від 2 до 100 символів.";
    }

    if ($data['product'] === '') {
        $errors['product'] = 'Вкажіть назву товару.';
    } elseif (textLength($data['product']) > 150) {
        $errors['product'] = 'Назва товару — не більше 150 символів.';
    }

    if (!array_key_exists($data['category'], CATEGORIES)) {
        $errors['category'] = 'Оберіть категорію зі списку.';
    }

    $quantity = filter_var($data['quantity'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 99]]);
    if ($quantity === false) {
        $errors['quantity'] = 'Кількість — ціле число від 1 до 99.';
    } else {
        $data['quantity'] = $quantity;
    }

    $price = filter_var($data['price'], FILTER_VALIDATE_FLOAT);
    if ($price === false || $price <= 0 || $price > 1000000) {
        $errors['price'] = 'Ціна — додатне число (до 1 000 000 ₴).';
    } else {
        $data['price'] = round($price, 2);
    }

    if ($data['email'] === '') {
        $errors['email'] = 'Вкажіть e-mail.';
    } elseif (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = 'Невірний формат e-mail (приклад: name@example.com).';
    }

    if ($data['phone'] !== '' && !preg_match('/^\+380\d{9}$/', $data['phone'])) {
        $errors['phone'] = 'Телефон у форматі +380XXXXXXXXX.';
    }

    if (textLength($data['comment']) > 500) {
        $errors['comment'] = 'Коментар — не більше 500 символів.';
    }

    return [$data, $errors];
}

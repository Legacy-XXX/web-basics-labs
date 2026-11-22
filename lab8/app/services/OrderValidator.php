<?php
declare(strict_types=1);

// Серверна валідація даних замовлення (одна для створення й редагування — DRY)
class OrderValidator
{
    public function validate(array $data): array
    {
        $errors = [];

        $nameLength = $this->length($data['customer_name']);
        if ($nameLength === 0) {
            $errors['customer_name'] = "Вкажіть ім'я покупця.";
        } elseif ($nameLength < 2 || $nameLength > 100) {
            $errors['customer_name'] = "Ім'я має містити від 2 до 100 символів.";
        }

        if ($data['product'] === '') {
            $errors['product'] = 'Вкажіть назву товару.';
        } elseif ($this->length($data['product']) > 150) {
            $errors['product'] = 'Назва товару — не більше 150 символів.';
        }

        if (!array_key_exists($data['category'], Order::CATEGORIES)) {
            $errors['category'] = 'Оберіть категорію зі списку.';
        }

        $quantity = filter_var($data['quantity'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 99]]);
        if ($quantity === false) {
            $errors['quantity'] = 'Кількість — ціле число від 1 до 99.';
        }

        $price = filter_var($data['price'], FILTER_VALIDATE_FLOAT);
        if ($price === false || $price <= 0 || $price > 1000000) {
            $errors['price'] = 'Ціна — додатне число (до 1 000 000 ₴).';
        }

        if ($data['email'] === '') {
            $errors['email'] = 'Вкажіть e-mail.';
        } elseif (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Невірний формат e-mail (приклад: name@example.com).';
        }

        if ($data['phone'] !== '' && !preg_match('/^\+380\d{9}$/', $data['phone'])) {
            $errors['phone'] = 'Телефон у форматі +380XXXXXXXXX.';
        }

        if ($this->length($data['comment']) > 500) {
            $errors['comment'] = 'Коментар — не більше 500 символів.';
        }

        return $errors;
    }

    // Довжина рядка в символах UTF-8
    private function length(string $value): int
    {
        return preg_match_all('/./us', $value);
    }
}

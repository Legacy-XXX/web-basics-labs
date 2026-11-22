<?php
declare(strict_types=1);

// Шаблон Factory: створення об'єктів Order з даних форми або з рядка бази
class OrderFactory
{
    // Приведення сирих даних форми до єдиного вигляду (обрізання пробілів, кома → крапка, телефон без пробілів)
    public static function normalize(array $input): array
    {
        return [
            'customer_name' => trim((string) ($input['customer_name'] ?? '')),
            'product' => trim((string) ($input['product'] ?? '')),
            'category' => trim((string) ($input['category'] ?? '')),
            'quantity' => trim((string) ($input['quantity'] ?? '')),
            'price' => str_replace(',', '.', trim((string) ($input['price'] ?? ''))),
            'email' => trim((string) ($input['email'] ?? '')),
            'phone' => preg_replace('/[\s()\-]/', '', (string) ($input['phone'] ?? '')),
            'comment' => trim((string) ($input['comment'] ?? '')),
        ];
    }

    // Створення з уже перевірених даних форми
    public static function fromInput(array $data, ?int $id = null): Order
    {
        return new Order(
            $id,
            $data['customer_name'],
            $data['product'],
            $data['category'],
            (int) $data['quantity'],
            round((float) $data['price'], 2),
            $data['email'],
            $data['phone'] !== '' ? $data['phone'] : null,
            $data['comment'] !== '' ? $data['comment'] : null,
        );
    }

    // Створення з рядка таблиці orders
    public static function fromRow(array $row): Order
    {
        return new Order(
            (int) $row['id'],
            $row['customer_name'],
            $row['product'],
            $row['category'],
            (int) $row['quantity'],
            (float) $row['price'],
            $row['email'],
            $row['phone'],
            $row['comment'],
            $row['created_at'],
        );
    }
}

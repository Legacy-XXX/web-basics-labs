<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

// Єдине підключення до бази (створюється при першому виклику)
function getConnection(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
    return $pdo;
}

// Запис замовлення (prepared statement захищає від SQL-ін'єкцій)
function insertOrder(array $order): int
{
    $sql = 'INSERT INTO orders (customer_name, product, category, quantity, price, email, phone, comment)
            VALUES (:customer_name, :product, :category, :quantity, :price, :email, :phone, :comment)';
    $stmt = getConnection()->prepare($sql);
    $stmt->execute([
        ':customer_name' => $order['customer_name'],
        ':product' => $order['product'],
        ':category' => $order['category'],
        ':quantity' => $order['quantity'],
        ':price' => $order['price'],
        ':email' => $order['email'],
        ':phone' => $order['phone'] !== '' ? $order['phone'] : null,
        ':comment' => $order['comment'] !== '' ? $order['comment'] : null,
    ]);
    return (int) getConnection()->lastInsertId();
}

// Вибірка всіх замовлень із сортуванням (стовпець береться лише з білого списку)
function getOrders(string $sort, string $direction): array
{
    $column = SORT_FIELDS[$sort][0] ?? 'created_at';
    $direction = strtolower($direction) === 'asc' ? 'ASC' : 'DESC';
    $sql = "SELECT * FROM orders ORDER BY {$column} {$direction}, id {$direction}";
    return getConnection()->query($sql)->fetchAll();
}

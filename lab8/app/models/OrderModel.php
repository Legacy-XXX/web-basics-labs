<?php
declare(strict_types=1);

// Модель: операції з таблицею orders (CRUD)
class OrderModel
{
    // Поля, за якими дозволено сортування (білий список)
    public const SORT_FIELDS = [
        'created_at' => 'Дата додавання',
        'product' => 'Назва товару',
        'price' => 'Ціна',
        'customer_name' => 'Покупець',
    ];

    private PDO $db;

    public function __construct()
    {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }

    public static function normalizeSort(mixed $sort): string
    {
        return array_key_exists((string) $sort, self::SORT_FIELDS) ? (string) $sort : 'created_at';
    }

    public static function normalizeDirection(mixed $direction): string
    {
        return $direction === 'asc' ? 'asc' : 'desc';
    }

    /** @return Order[] */
    public function findAll(string $sort, string $direction): array
    {
        $column = self::normalizeSort($sort);
        $dir = self::normalizeDirection($direction) === 'asc' ? 'ASC' : 'DESC';
        $rows = $this->db->query("SELECT * FROM orders ORDER BY {$column} {$dir}, id {$dir}")->fetchAll();
        return array_map([OrderFactory::class, 'fromRow'], $rows);
    }

    public function findById(int $id): ?Order
    {
        $stmt = $this->db->prepare('SELECT * FROM orders WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ? OrderFactory::fromRow($row) : null;
    }

    public function insert(Order $order): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO orders (customer_name, product, category, quantity, price, email, phone, comment)
             VALUES (:customer_name, :product, :category, :quantity, :price, :email, :phone, :comment)'
        );
        $stmt->execute($this->params($order));
        return (int) $this->db->lastInsertId();
    }

    public function update(Order $order): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE orders SET customer_name = :customer_name, product = :product, category = :category,
                    quantity = :quantity, price = :price, email = :email, phone = :phone, comment = :comment
             WHERE id = :id'
        );
        $stmt->execute($this->params($order) + [':id' => $order->getId()]);
        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM orders WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }

    // Спільні параметри для INSERT і UPDATE
    private function params(Order $order): array
    {
        return [
            ':customer_name' => $order->getCustomerName(),
            ':product' => $order->getProduct(),
            ':category' => $order->getCategory(),
            ':quantity' => $order->getQuantity(),
            ':price' => $order->getPrice(),
            ':email' => $order->getEmail(),
            ':phone' => $order->getPhone(),
            ':comment' => $order->getComment(),
        ];
    }
}

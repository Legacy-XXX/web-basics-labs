<?php
declare(strict_types=1);

// Підключення до бази даних — шаблон Singleton (один об'єкт PDO на весь запит)
class DatabaseConnection
{
    private static ?DatabaseConnection $instance = null;
    private PDO $connection;

    private function __construct()
    {
        $config = (require APP_ROOT . '/config/config.php')['db'];
        $dsn = "mysql:host={$config['host']};dbname={$config['name']};charset={$config['charset']}";
        $this->connection = new PDO($dsn, $config['user'], $config['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    // Клонування заборонене, щоб не з'явилось друге підключення
    private function __clone()
    {
    }

    public static function getInstance(): DatabaseConnection
    {
        if (self::$instance === null) {
            self::$instance = new DatabaseConnection();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}

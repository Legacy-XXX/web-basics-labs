<?php
declare(strict_types=1);

// Обгортка над даними HTTP-запиту: метод, параметри URL і тіло запиту
class Request
{
    private array $body;

    public function __construct()
    {
        $this->body = $this->parseBody();
    }

    // Метод запиту з урахуванням прихованого поля _method (HTML-форми підтримують лише GET і POST)
    public function method(): string
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        if ($method === 'POST' && isset($_POST['_method'])) {
            return strtoupper((string) $_POST['_method']);
        }
        return $method;
    }

    // Чи очікує клієнт JSON (запит через fetch з методом DELETE)
    public function wantsJson(): bool
    {
        return ($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST'
            || str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json');
    }

    public function query(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->body[$key] ?? $default;
    }

    public function all(): array
    {
        return $this->body;
    }

    private function parseBody(): array
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            return $_POST;
        }
        $raw = (string) file_get_contents('php://input');
        $json = json_decode($raw, true);
        if (is_array($json)) {
            return $json;
        }
        parse_str($raw, $data);
        return $data;
    }
}

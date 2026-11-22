<?php
declare(strict_types=1);

// Базовий контролер: спільні залежності та відповіді (DRY)
abstract class Controller
{
    protected Request $request;
    protected View $view;

    public function __construct()
    {
        $this->request = new Request();
        $this->view = new View();
    }

    abstract public function handle(): void;

    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function notFound(string $message): void
    {
        http_response_code(404);
        $this->view->render('partials/error', ['message' => $message], 'Не знайдено');
    }
}

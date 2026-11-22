<?php
declare(strict_types=1);

// Відображення шаблонів: хедер + сторінка + футер
class View
{
    public function render(string $template, array $variables = [], string $title = 'Клац', string $activePage = ''): void
    {
        $this->include($template, $variables + ['pageTitle' => $title, 'activePage' => $activePage]);
    }

    // Шаблони підключаються в окремій області видимості, щоб імена змінних не перетиналися
    private function include(string $__template, array $__variables): void
    {
        extract($__variables, EXTR_SKIP);
        require APP_ROOT . '/views/layout/header.php';
        require APP_ROOT . "/views/{$__template}.php";
        require APP_ROOT . '/views/layout/footer.php';
    }
}

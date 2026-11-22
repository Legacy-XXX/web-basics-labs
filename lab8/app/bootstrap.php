<?php
declare(strict_types=1);

// Точка ініціалізації застосунку: константи, допоміжні функції та автозавантаження класів
define('APP_ROOT', __DIR__);

require_once APP_ROOT . '/core/helpers.php';

spl_autoload_register(function (string $class): void {
    foreach (['core', 'models', 'services', 'controllers'] as $directory) {
        $file = APP_ROOT . "/{$directory}/{$class}.php";
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});

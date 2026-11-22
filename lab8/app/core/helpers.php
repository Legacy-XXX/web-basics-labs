<?php
declare(strict_types=1);

// Екранування для виводу в HTML (захист від XSS)
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// Форматування суми у гривнях
function money(float $value): string
{
    return number_format($value, 2, ',', ' ') . ' ₴';
}

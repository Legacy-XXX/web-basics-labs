<?php
declare(strict_types=1);

require_once __DIR__ . '/io.php';

// Перевірка формату «число1 оператор число2»; повертає [число1, оператор, число2] або null
function validate(string $inputParam): ?array
{
    $number = '-?\d+(?:[.,]\d+)?';
    $pattern = "/^\s*({$number})\s*(\*\*|[+\-*\/%])\s*({$number})\s*$/";
    if (!preg_match($pattern, $inputParam, $m)) {
        return null;
    }
    $toNumber = fn(string $s): float => (float) str_replace(',', '.', $s);
    return [$toNumber($m[1]), $m[2], $toNumber($m[3])];
}

// Обчислення виразу: окрема анонімна функція для кожної операції
function calculate(float $num1, string $operator, float $num2): float
{
    $add = function (float $a, float $b): float { return $a + $b; };
    $subtract = function (float $a, float $b): float { return $a - $b; };
    $multiply = function (float $a, float $b): float { return $a * $b; };
    $divide = function (float $a, float $b): float {
        if ($b == 0) {
            throw new DivisionByZeroError('Ділення на нуль неможливе');
        }
        return $a / $b;
    };
    $power = function (float $a, float $b): float { return $a ** $b; };
    $modulo = function (float $a, float $b): float {
        if ($b == 0) {
            throw new DivisionByZeroError('Залишок від ділення на нуль не визначений');
        }
        return fmod($a, $b);
    };

    $operation = match ($operator) {
        '+' => $add,
        '-' => $subtract,
        '*' => $multiply,
        '/' => $divide,
        '**' => $power,
        '%' => $modulo,
        default => throw new InvalidArgumentException("Невідомий оператор: {$operator}"),
    };

    return $operation($num1, $num2);
}

// Красивий вивід: без зайвих нулів після коми
function formatNumber(float $value): string
{
    if (is_infinite($value) || is_nan($value)) {
        return (string) $value;
    }
    return rtrim(rtrim(number_format($value, 6, '.', ''), '0'), '.');
}

echo '=== Консольний калькулятор ===' . PHP_EOL;
echo 'Оператори: +  -  *  /  **  %. Для виходу введи exit.' . PHP_EOL;

while (true) {
    $input = readInput(PHP_EOL . 'Введіть вираз (напр. 5 + 3): ');
    if (in_array(strtolower($input), ['exit', 'q', 'вихід'], true)) {
        break;
    }

    $parsed = validate($input);
    if ($parsed === null) {
        echo 'Помилка: невірний формат. Використовуйте «число оператор число», напр. 10 % 3.' . PHP_EOL;
        continue;
    }

    [$num1, $operator, $num2] = $parsed;
    try {
        echo 'Результат: ' . formatNumber(calculate($num1, $operator, $num2)) . PHP_EOL;
    } catch (DivisionByZeroError | InvalidArgumentException $e) {
        echo 'Помилка: ' . $e->getMessage() . PHP_EOL;
    }
}
echo 'До побачення!' . PHP_EOL;

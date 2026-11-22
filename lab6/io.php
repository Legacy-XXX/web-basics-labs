<?php
declare(strict_types=1);

// Спільна функція: вивести підказку й прочитати рядок із консолі
function readInput(string $prompt): string
{
    echo $prompt;
    $line = fgets(STDIN);
    if ($line === false) {
        echo PHP_EOL . 'Введення завершено.' . PHP_EOL;
        exit(0);
    }
    return trim($line);
}

// Запитати, чи зіграти ще раз
function askReplay(): bool
{
    $answer = readInput('Зіграти ще раз? (т/н): ');
    return in_array($answer, ['т', 'Т', 'так', 'Так', 'y', 'Y', 'yes'], true);
}

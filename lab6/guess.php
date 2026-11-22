<?php
declare(strict_types=1);

require_once __DIR__ . '/io.php';

const MIN_NUMBER = 1;
const MAX_NUMBER = 100;

// Перевірка вхідних даних: ціле число в діапазоні 1–100, інакше null
function validate(string $inputParam): ?int
{
    if (!preg_match('/^-?\d+$/', $inputParam)) {
        return null;
    }
    $number = (int) $inputParam;
    return ($number >= MIN_NUMBER && $number <= MAX_NUMBER) ? $number : null;
}

// Основна логіка гри
function playGame(int $maxAttempts): void
{
    $secret = rand(MIN_NUMBER, MAX_NUMBER);

    // Анонімна функція порівняння: -1 — менше, 0 — вгадано, 1 — більше
    $compare = function (int $guess) use ($secret): int {
        return $guess <=> $secret;
    };

    echo PHP_EOL . 'Я загадав число від 1 до 100. Спробуй вгадати. У тебе ' . $maxAttempts . ' спроб.' . PHP_EOL;

    $attempt = 1;
    while ($attempt <= $maxAttempts) {
        $guess = validate(readInput("Спроба {$attempt}: "));
        if ($guess === null) {
            echo 'Помилка: введи ціле число від 1 до 100. Спроба не зарахована.' . PHP_EOL;
            continue;
        }

        $result = $compare($guess);
        if ($result === 0) {
            echo "🎉 Вітаю! Ти вгадав число {$secret} за {$attempt} спроб(и)." . PHP_EOL;
            return;
        }
        echo ($result > 0 ? 'Спробуй менше.' : 'Спробуй більше.') . PHP_EOL;
        $attempt++;
    }

    echo "😞 Спроби закінчились — ти програв. Я загадав число {$secret}." . PHP_EOL;
}

echo '=== Гра «Guess a Number» ===' . PHP_EOL;
do {
    playGame(7);
} while (askReplay());
echo 'Дякую за гру!' . PHP_EOL;

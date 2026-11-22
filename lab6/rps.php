<?php
declare(strict_types=1);

require_once __DIR__ . '/io.php';

const CHOICES = [0 => 'камінь', 1 => 'ножиці', 2 => 'папір'];
const ROUNDS = 3;

// Перевірка вхідних даних: номер варіанта 0, 1 або 2, інакше null
function validate(string $inputParam): ?int
{
    if (!preg_match('/^\d$/', $inputParam)) {
        return null;
    }
    $choice = (int) $inputParam;
    return array_key_exists($choice, CHOICES) ? $choice : null;
}

// Анонімна функція: випадковий вибір комп'ютера
$getComputerChoice = function (): int {
    return array_rand(CHOICES);
};

// Раунд: повертає 'user', 'computer' або 'draw'
function playRound(int $userChoice, callable $getComputerChoice): string
{
    $computerChoice = $getComputerChoice();
    echo 'Я вибрав: ' . CHOICES[$computerChoice] . PHP_EOL;

    if ($userChoice === $computerChoice) {
        echo 'Нічия!' . PHP_EOL;
        return 'draw';
    }
    // Камінь б'є ножиці, ножиці б'ють папір, папір б'є камінь
    if (($userChoice + 1) % 3 === $computerChoice) {
        echo 'Ти виграв цей раунд!' . PHP_EOL;
        return 'user';
    }
    echo 'Цей раунд виграв я!' . PHP_EOL;
    return 'computer';
}

function playGame(callable $getComputerChoice): void
{
    $score = ['user' => 0, 'computer' => 0];

    for ($round = 1; $round <= ROUNDS; $round++) {
        echo PHP_EOL . "Раунд {$round}. Обери:" . PHP_EOL;
        foreach (CHOICES as $key => $name) {
            echo "[{$key}] {$name}" . PHP_EOL;
        }

        $choice = validate(readInput('-> '));
        while ($choice === null) {
            echo 'Помилка: введи 0, 1 або 2.' . PHP_EOL;
            $choice = validate(readInput('-> '));
        }

        $winner = playRound($choice, $getComputerChoice);
        if ($winner !== 'draw') {
            $score[$winner]++;
        }
        echo "Рахунок: ти {$score['user']} : {$score['computer']} я" . PHP_EOL;
    }

    echo PHP_EOL . "Підсумковий рахунок: ти {$score['user']} : {$score['computer']} я" . PHP_EOL;
    echo match ($score['user'] <=> $score['computer']) {
        1 => '🏆 Переможець — ти!',
        -1 => '🤖 Переможець — комп\'ютер!',
        0 => '🤝 Нічия!',
    } . PHP_EOL;
}

echo '=== Гра «Камінь, Ножиці, Папір» ===' . PHP_EOL;
do {
    playGame($getComputerChoice);
} while (askReplay());
echo 'Дякую за гру!' . PHP_EOL;

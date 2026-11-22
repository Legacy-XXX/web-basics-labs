<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/validation.php';
require_once __DIR__ . '/includes/db.php';

$old = [];
$errors = [];
$dbError = null;
$successId = isset($_GET['success']) ? (int) $_GET['success'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$old, $errors] = validateOrder($_POST);

    if (!$errors) {
        try {
            $id = insertOrder($old);
            // Post/Redirect/Get: після успішного запису перенаправляємо, щоб уникнути повторної відправки
            header('Location: order.php?success=' . $id);
            exit;
        } catch (PDOException $e) {
            $dbError = 'Не вдалося зберегти замовлення в базі даних: ' . $e->getMessage();
        }
    }
}

// Допоміжні функції для полів форми
$value = fn(string $field): string => e((string) ($old[$field] ?? ''));
$fieldClass = fn(string $field): string => 'w-full border rounded-lg p-2 focus:outline-none focus:ring-2 '
    . (isset($errors[$field]) ? 'border-red-400 bg-red-50 focus:ring-red-400' : 'border-slate-300 focus:ring-klats-500');
$errorText = fn(string $field): string => isset($errors[$field])
    ? '<p class="text-sm text-red-600 mt-1">' . e($errors[$field]) . '</p>' : '';

$pageTitle = 'Нове замовлення';
$activePage = 'order';
require __DIR__ . '/includes/header.php';
?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <section class="card lg:col-span-2">
    <h1 class="text-4xl font-display font-bold text-blue-600">Нове замовлення</h1>
    <p class="text-base leading-normal text-slate-600">Заповніть форму — замовлення буде збережено в базі даних MySQL.</p>

    <?php if ($successId > 0): ?>
      <div class="rounded-xl border border-green-300 bg-green-50 text-green-800 p-4">
        ✅ Замовлення №<?= $successId ?> успішно додано до бази даних.
        <a href="orders.php" class="underline font-semibold">Переглянути всі замовлення</a>
      </div>
    <?php endif; ?>

    <?php if ($errors): ?>
      <div class="rounded-xl border border-red-300 bg-red-50 text-red-800 p-4">
        <p class="font-semibold">Форму заповнено з помилками (<?= count($errors) ?>):</p>
        <ul class="list-disc ml-5 text-sm mt-1">
          <?php foreach ($errors as $message): ?>
            <li><?= e($message) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php if ($dbError): ?>
      <div class="rounded-xl border border-red-300 bg-red-50 text-red-800 p-4"><?= e($dbError) ?></div>
    <?php endif; ?>

    <form method="POST" action="order.php" class="grid grid-cols-1 md:grid-cols-2 gap-4" novalidate>
      <label class="flex flex-col gap-1">
        <span class="text-sm font-medium">Ім'я покупця *</span>
        <input type="text" name="customer_name" value="<?= $value('customer_name') ?>" placeholder="Іван Петренко" class="<?= $fieldClass('customer_name') ?>">
        <?= $errorText('customer_name') ?>
      </label>
      <label class="flex flex-col gap-1">
        <span class="text-sm font-medium">Назва товару *</span>
        <input type="text" name="product" value="<?= $value('product') ?>" placeholder="Миша Klats Storm" class="<?= $fieldClass('product') ?>">
        <?= $errorText('product') ?>
      </label>
      <label class="flex flex-col gap-1">
        <span class="text-sm font-medium">Категорія *</span>
        <select name="category" class="<?= $fieldClass('category') ?> bg-white">
          <option value="">— оберіть категорію —</option>
          <?php foreach (CATEGORIES as $key => [$label]): ?>
            <option value="<?= $key ?>" <?= ($old['category'] ?? '') === $key ? 'selected' : '' ?>><?= $label ?></option>
          <?php endforeach; ?>
        </select>
        <?= $errorText('category') ?>
      </label>
      <div class="grid grid-cols-2 gap-4">
        <label class="flex flex-col gap-1">
          <span class="text-sm font-medium">Кількість *</span>
          <input type="number" name="quantity" min="1" max="99" value="<?= $value('quantity') ?>" placeholder="1" class="<?= $fieldClass('quantity') ?>">
          <?= $errorText('quantity') ?>
        </label>
        <label class="flex flex-col gap-1">
          <span class="text-sm font-medium">Ціна, ₴ *</span>
          <input type="text" inputmode="decimal" name="price" value="<?= $value('price') ?>" placeholder="1599" class="<?= $fieldClass('price') ?>">
          <?= $errorText('price') ?>
        </label>
      </div>
      <label class="flex flex-col gap-1">
        <span class="text-sm font-medium">E-mail *</span>
        <input type="email" name="email" value="<?= $value('email') ?>" placeholder="name@example.com" class="<?= $fieldClass('email') ?>">
        <?= $errorText('email') ?>
      </label>
      <label class="flex flex-col gap-1">
        <span class="text-sm font-medium">Телефон</span>
        <input type="tel" name="phone" value="<?= $value('phone') ?>" placeholder="+380671234567" class="<?= $fieldClass('phone') ?>">
        <?= $errorText('phone') ?>
      </label>
      <label class="flex flex-col gap-1 md:col-span-2">
        <span class="text-sm font-medium">Коментар до замовлення</span>
        <textarea name="comment" rows="3" placeholder="Колір, побажання щодо доставки…" class="<?= $fieldClass('comment') ?>"><?= $value('comment') ?></textarea>
        <?= $errorText('comment') ?>
      </label>
      <div class="md:col-span-2 flex flex-col xs:flex-row gap-3">
        <button type="submit" class="bg-green-500 text-white p-2 px-6 rounded-lg hover:bg-green-700 font-semibold transition">Надіслати замовлення</button>
        <a href="orders.php" class="text-center border border-klats-200 text-klats-700 p-2 px-6 rounded-lg hover:bg-klats-100 transition">Список замовлень</a>
      </div>
    </form>
  </section>

  <aside class="card bg-klats-900 text-white h-fit">
    <h2 class="font-display text-xl font-bold">Правила заповнення</h2>
    <ul class="text-sm leading-normal text-klats-100 space-y-2 list-disc ml-5">
      <li>Поля з * — обов'язкові.</li>
      <li>Кількість — від 1 до 99.</li>
      <li>Ціна — додатне число, можна з копійками.</li>
      <li>E-mail у форматі name@example.com.</li>
      <li>Телефон (необов'язково) — +380XXXXXXXXX.</li>
    </ul>
    <img src="assets/img/mouse.svg" alt="Миша" class="rounded-xl mt-2">
  </aside>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>

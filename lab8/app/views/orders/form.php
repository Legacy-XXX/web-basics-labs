<?php
// Спільна форма для створення та редагування замовлення.
// Змінні: $mode ('create' | 'edit'), $id, $data (значення полів), $errors
$isEdit = $mode === 'edit';
$value = fn(string $field): string => e($data[$field] ?? '');
$fieldClass = fn(string $field): string => 'w-full border rounded-lg p-2 focus:outline-none focus:ring-2 '
    . (isset($errors[$field]) ? 'border-red-400 bg-red-50 focus:ring-red-400' : 'border-slate-300 focus:ring-klats-500');
$fieldError = function (string $field) use ($errors): void {
    require APP_ROOT . '/views/partials/field-error.php';
};
?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <section class="card lg:col-span-2">
    <h1 class="text-4xl font-display font-bold text-blue-600">
      <?= $isEdit ? 'Редагування замовлення №' . (int) $id : 'Нове замовлення' ?>
    </h1>
    <p class="text-base leading-normal text-slate-600">
      <?= $isEdit
          ? 'Змініть потрібні поля та збережіть. Додано: ' . e(date('d.m.Y H:i:s', strtotime($createdAt))) . '.'
          : 'Заповніть форму — замовлення буде збережено в базі даних MySQL.' ?>
    </p>

    <?php if ($errors): ?>
      <div class="rounded-xl border border-red-300 bg-red-50 text-red-800 p-4">
        <p class="font-semibold">Серверна валідація: знайдено помилок — <?= count($errors) ?></p>
        <ul class="list-disc ml-5 text-sm mt-1">
          <?php foreach ($errors as $message): ?>
            <li><?= e($message) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="POST" action="<?= $isEdit ? 'edit.php' : 'create.php' ?>" class="grid grid-cols-1 md:grid-cols-2 gap-4" novalidate>
      <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= (int) $id ?>">
      <?php endif; ?>

      <label class="flex flex-col gap-1">
        <span class="text-sm font-medium">Ім'я покупця *</span>
        <input type="text" name="customer_name" value="<?= $value('customer_name') ?>" placeholder="Іван Петренко" class="<?= $fieldClass('customer_name') ?>">
        <?php $fieldError('customer_name'); ?>
      </label>
      <label class="flex flex-col gap-1">
        <span class="text-sm font-medium">Назва товару *</span>
        <input type="text" name="product" value="<?= $value('product') ?>" placeholder="Миша Klats Storm" class="<?= $fieldClass('product') ?>">
        <?php $fieldError('product'); ?>
      </label>
      <label class="flex flex-col gap-1">
        <span class="text-sm font-medium">Категорія *</span>
        <select name="category" class="<?= $fieldClass('category') ?> bg-white">
          <option value="">— оберіть категорію —</option>
          <?php foreach (Order::CATEGORIES as $key => [$label]): ?>
            <option value="<?= $key ?>" <?= ($data['category'] ?? '') === $key ? 'selected' : '' ?>><?= $label ?></option>
          <?php endforeach; ?>
        </select>
        <?php $fieldError('category'); ?>
      </label>
      <div class="grid grid-cols-2 gap-4">
        <label class="flex flex-col gap-1">
          <span class="text-sm font-medium">Кількість *</span>
          <input type="number" name="quantity" min="1" max="99" value="<?= $value('quantity') ?>" placeholder="1" class="<?= $fieldClass('quantity') ?>">
          <?php $fieldError('quantity'); ?>
        </label>
        <label class="flex flex-col gap-1">
          <span class="text-sm font-medium">Ціна, ₴ *</span>
          <input type="text" inputmode="decimal" name="price" value="<?= $value('price') ?>" placeholder="1599" class="<?= $fieldClass('price') ?>">
          <?php $fieldError('price'); ?>
        </label>
      </div>
      <label class="flex flex-col gap-1">
        <span class="text-sm font-medium">E-mail *</span>
        <input type="email" name="email" value="<?= $value('email') ?>" placeholder="name@example.com" class="<?= $fieldClass('email') ?>">
        <?php $fieldError('email'); ?>
      </label>
      <label class="flex flex-col gap-1">
        <span class="text-sm font-medium">Телефон</span>
        <input type="tel" name="phone" value="<?= $value('phone') ?>" placeholder="+380671234567" class="<?= $fieldClass('phone') ?>">
        <?php $fieldError('phone'); ?>
      </label>
      <label class="flex flex-col gap-1 md:col-span-2">
        <span class="text-sm font-medium">Коментар до замовлення</span>
        <textarea name="comment" rows="3" placeholder="Колір, побажання щодо доставки…" class="<?= $fieldClass('comment') ?>"><?= $value('comment') ?></textarea>
        <?php $fieldError('comment'); ?>
      </label>
      <div class="md:col-span-2 flex flex-col xs:flex-row gap-3">
        <button type="submit" class="bg-green-500 text-white p-2 px-6 rounded-lg hover:bg-green-700 font-semibold transition">
          <?= $isEdit ? 'Зберегти зміни' : 'Надіслати замовлення' ?>
        </button>
        <a href="list.php" class="text-center border border-klats-200 text-klats-700 p-2 px-6 rounded-lg hover:bg-klats-100 transition">
          <?= $isEdit ? 'Скасувати' : 'Список замовлень' ?>
        </a>
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
    <img src="assets/img/<?= $isEdit ? 'keyboard' : 'mouse' ?>.svg" alt="" class="rounded-xl mt-2">
  </aside>
</div>

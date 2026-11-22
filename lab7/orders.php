<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/validation.php';
require_once __DIR__ . '/includes/db.php';

// Параметри сортування з URL ($_GET) з перевіркою за білим списком
$sort = array_key_exists($_GET['sort'] ?? '', SORT_FIELDS) ? $_GET['sort'] : 'created_at';
$order = ($_GET['order'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

$orders = [];
$dbError = null;
try {
    $orders = getOrders($sort, $order);
} catch (PDOException $e) {
    $dbError = 'Не вдалося отримати дані з бази: ' . $e->getMessage();
}

$money = fn(float $value): string => number_format($value, 2, ',', ' ') . ' ₴';
$total = array_sum(array_map(fn(array $o): float => $o['price'] * $o['quantity'], $orders));

$pageTitle = 'Замовлення';
$activePage = 'orders';
$scripts = ['assets/js/sort.js'];
require __DIR__ . '/includes/header.php';
?>
<section class="card">
  <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
    <div>
      <h1 class="text-4xl font-display font-bold text-blue-600">Замовлення</h1>
      <p class="text-base leading-normal text-slate-600 mt-1">
        Усього записів: <b><?= count($orders) ?></b> · Загальна сума: <b><?= $money($total) ?></b>
      </p>
    </div>
    <div class="flex flex-col xs:flex-row gap-2 xs:items-center">
      <label for="sort-field" class="text-sm font-medium">Сортувати за:</label>
      <select id="sort-field" class="border border-slate-300 rounded-lg p-2 bg-white">
        <?php foreach (SORT_FIELDS as $key => [, $label]): ?>
          <option value="<?= $key ?>" <?= $key === $sort ? 'selected' : '' ?>><?= $label ?></option>
        <?php endforeach; ?>
      </select>
      <button id="sort-order" type="button" data-order="<?= $order ?>"
              class="bg-klats-600 text-white p-2 px-4 rounded-lg hover:bg-klats-700 transition">
        <?= $order === 'asc' ? '↑ За зростанням' : '↓ За спаданням' ?>
      </button>
      <a href="order.php" class="bg-green-500 text-white p-2 px-4 rounded-lg hover:bg-green-700 text-center transition">+ Додати</a>
    </div>
  </div>

  <?php if ($dbError): ?>
    <div class="rounded-xl border border-red-300 bg-red-50 text-red-800 p-4"><?= e($dbError) ?></div>
  <?php elseif (!$orders): ?>
    <p class="text-slate-500">Замовлень ще немає. <a href="order.php" class="underline">Додати перше</a>.</p>
  <?php else: ?>
    <div class="overflow-x-auto rounded-xl border border-klats-100">
      <table class="w-full text-sm">
        <thead class="bg-klats-100 text-klats-900 text-left whitespace-nowrap">
          <tr>
            <th class="p-3">№</th>
            <th class="p-3">Додано</th>
            <th class="p-3">Покупець</th>
            <th class="p-3">Товар</th>
            <th class="p-3 text-right">К-сть</th>
            <th class="p-3 text-right">Ціна</th>
            <th class="p-3 text-right">Сума</th>
            <th class="p-3">Контакти</th>
            <th class="p-3">Коментар</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-klats-100">
          <?php foreach ($orders as $row): ?>
            <?php [$categoryLabel, $categoryImage] = CATEGORIES[$row['category']] ?? [$row['category'], 'assets/img/logo.svg']; ?>
            <tr class="hover:bg-klats-50 align-top">
              <td class="p-3 font-semibold text-klats-700"><?= (int) $row['id'] ?></td>
              <td class="p-3 whitespace-nowrap">
                <?= e(date('d.m.Y', strtotime($row['created_at']))) ?>
                <span class="block text-xs text-slate-500"><?= e(date('H:i:s', strtotime($row['created_at']))) ?></span>
              </td>
              <td class="p-3 min-w-[8rem]"><?= e($row['customer_name']) ?></td>
              <td class="p-3 min-w-[13rem]">
                <div class="flex items-center gap-2">
                  <img src="<?= e($categoryImage) ?>" alt="" class="w-10 h-8 rounded">
                  <div>
                    <p class="font-medium text-klats-900"><?= e($row['product']) ?></p>
                    <p class="text-xs text-slate-500"><?= e($categoryLabel) ?></p>
                  </div>
                </div>
              </td>
              <td class="p-3 text-right"><?= (int) $row['quantity'] ?></td>
              <td class="p-3 text-right whitespace-nowrap"><?= $money((float) $row['price']) ?></td>
              <td class="p-3 text-right whitespace-nowrap font-semibold"><?= $money($row['price'] * $row['quantity']) ?></td>
              <td class="p-3">
                <p><?= e($row['email']) ?></p>
                <p class="text-xs text-slate-500"><?= e($row['phone'] ?? '—') ?></p>
              </td>
              <td class="p-3 max-w-[12rem] [overflow-wrap:anywhere] text-slate-600"><?= e($row['comment'] ?? '') ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>

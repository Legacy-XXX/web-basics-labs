<?php
// Список замовлень. Змінні: $orders (Order[]), $sort, $order, $flash
$total = array_sum(array_map(fn(Order $o): float => $o->getTotal(), $orders));
?>
<section class="card">
  <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
    <div>
      <h1 class="text-4xl font-display font-bold text-blue-600">Замовлення</h1>
      <p class="text-base leading-normal text-slate-600 mt-1">
        Усього записів: <b><?= count($orders) ?></b> · Загальна сума: <b><?= money($total) ?></b>
      </p>
    </div>
    <div class="flex flex-col xs:flex-row gap-2 xs:items-center">
      <label for="sort-field" class="text-sm font-medium">Сортувати за:</label>
      <select id="sort-field" class="border border-slate-300 rounded-lg p-2 bg-white">
        <?php foreach (OrderModel::SORT_FIELDS as $key => $label): ?>
          <option value="<?= $key ?>" <?= $key === $sort ? 'selected' : '' ?>><?= $label ?></option>
        <?php endforeach; ?>
      </select>
      <button id="sort-order" type="button" data-order="<?= $order ?>"
              class="bg-klats-600 text-white p-2 px-4 rounded-lg hover:bg-klats-700 transition">
        <?= $order === 'asc' ? '↑ За зростанням' : '↓ За спаданням' ?>
      </button>
      <a href="create.php" class="bg-green-500 text-white p-2 px-4 rounded-lg hover:bg-green-700 text-center transition">+ Додати</a>
    </div>
  </div>

  <?php if ($flash): ?>
    <div class="rounded-xl border border-green-300 bg-green-50 text-green-800 p-4"><?= e($flash) ?></div>
  <?php endif; ?>

  <?php if (!$orders): ?>
    <p class="text-slate-500">Замовлень ще немає. <a href="create.php" class="underline">Додати перше</a>.</p>
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
            <th class="p-3 text-right">Сума</th>
            <th class="p-3">Контакти</th>
            <th class="p-3 text-center">Дії</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-klats-100">
          <?php foreach ($orders as $item): ?>
            <tr class="hover:bg-klats-50 align-top">
              <td class="p-3 font-semibold text-klats-700"><?= $item->getId() ?></td>
              <td class="p-3 whitespace-nowrap">
                <?= e(date('d.m.Y', strtotime($item->getCreatedAt()))) ?>
                <span class="block text-xs text-slate-500"><?= e(date('H:i:s', strtotime($item->getCreatedAt()))) ?></span>
              </td>
              <td class="p-3 min-w-[8rem]"><?= e($item->getCustomerName()) ?></td>
              <td class="p-3 min-w-[13rem]">
                <div class="flex items-center gap-2">
                  <img src="<?= e($item->getCategoryImage()) ?>" alt="" class="w-10 h-8 rounded">
                  <div>
                    <p class="font-medium text-klats-900"><?= e($item->getProduct()) ?></p>
                    <p class="text-xs text-slate-500"><?= e($item->getCategoryLabel()) ?> · <?= money($item->getPrice()) ?></p>
                  </div>
                </div>
              </td>
              <td class="p-3 text-right"><?= $item->getQuantity() ?></td>
              <td class="p-3 text-right whitespace-nowrap font-semibold"><?= money($item->getTotal()) ?></td>
              <td class="p-3">
                <p><?= e($item->getEmail()) ?></p>
                <p class="text-xs text-slate-500"><?= e($item->getPhone() ?? '—') ?></p>
              </td>
              <td class="p-3">
                <div class="flex gap-2 justify-center">
                  <a href="edit.php?id=<?= $item->getId() ?>"
                     class="bg-klats-600 text-white px-3 py-1.5 rounded-lg hover:bg-klats-700 transition">Редагувати</a>
                  <form method="POST" action="delete.php" class="delete-form">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="id" value="<?= $item->getId() ?>">
                    <button type="submit" class="bg-red-500 text-white px-3 py-1.5 rounded-lg hover:bg-red-700 transition">Видалити</button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</section>

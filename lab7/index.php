<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/validation.php';

$pageTitle = 'Головна';
$activePage = 'home';
$scripts = ['assets/js/shopping.js'];
require __DIR__ . '/includes/header.php';
?>
<section class="bg-gradient-to-r from-klats-700 to-klats-500 rounded-card shadow-card p-6 md:p-8 text-white flex flex-col md:flex-row md:items-center gap-6">
  <div class="flex-1">
    <p class="uppercase tracking-widest text-zap-400 text-sm font-semibold">Плануй і замовляй</p>
    <h1 class="text-4xl font-display font-bold mt-2">Список покупок і замовлення</h1>
    <p class="text-base leading-normal mt-3 text-klats-100 max-w-2xl">
      Склади список бажаної периферії, а коли визначишся — оформи замовлення.
      Замовлення зберігаються в базі даних MySQL.
    </p>
  </div>
  <a href="order.php" class="bg-green-500 text-white p-2 px-6 rounded-lg hover:bg-green-700 font-semibold text-center transition">
    Оформити замовлення
  </a>
</section>

<!-- Триколонковий макет із лабораторних робіт №2–3 -->
<section class="grid grid-cols-1 lg:grid-cols-[1fr_1.4fr_0.9fr] gap-4 p-4 mt-6 bg-white/60 rounded-card">
  <div class="card">
    <h2 class="font-display text-xl font-bold text-klats-900">Додати покупку</h2>
    <form id="purchase-form" class="flex flex-col gap-4" novalidate>
      <label class="flex flex-col gap-1">
        <span class="text-sm font-medium text-slate-700">Назва покупки</span>
        <input id="purchase-name" type="text" placeholder="Напр.: Миша Klats Storm"
               class="border border-slate-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-klats-500">
      </label>
      <label class="flex flex-col gap-1">
        <span class="text-sm font-medium text-slate-700">Вартість, ₴</span>
        <input id="purchase-price" type="number" min="1" step="0.01" placeholder="Напр.: 1599"
               class="border border-slate-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-klats-500">
      </label>
      <label class="flex flex-col gap-1">
        <span class="text-sm font-medium text-slate-700">Тип пристрою (зображення)</span>
        <select id="purchase-type" class="border border-slate-300 rounded-lg p-2 bg-white focus:outline-none focus:ring-2 focus:ring-klats-500"></select>
      </label>
      <p id="form-error" class="hidden text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg p-2"></p>
      <button type="submit" class="bg-green-500 text-white p-2 rounded-lg hover:bg-green-700 font-semibold transition">Додати</button>
    </form>
  </div>

  <div class="card">
    <h2 class="font-display text-xl font-bold text-klats-900">Список покупок</h2>
    <p id="empty-message" class="text-slate-500 text-sm">Список порожній — додай першу покупку.</p>
    <ul id="purchase-list" class="flex flex-col gap-3"></ul>
  </div>

  <aside class="card bg-klats-900 text-white">
    <h2 class="font-display text-xl font-bold">Підсумок</h2>
    <div class="grid grid-cols-2 lg:grid-cols-1 gap-3">
      <div class="rounded-xl bg-white/10 p-4">
        <p class="text-sm text-klats-200">Кількість покупок</p>
        <p id="stat-count" class="text-3xl font-bold">0</p>
      </div>
      <div class="rounded-xl bg-white/10 p-4">
        <p class="text-sm text-klats-200">Загальна вартість</p>
        <p id="stat-total" class="text-3xl font-bold text-zap-400">0,00 ₴</p>
      </div>
    </div>
    <a href="orders.php" class="text-sm underline text-klats-200 hover:text-white">Переглянути збережені замовлення →</a>
  </aside>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>

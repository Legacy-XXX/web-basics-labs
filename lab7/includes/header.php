<?php
// Спільний хедер. Перед підключенням сторінка задає $pageTitle і $activePage
$pageTitle = $pageTitle ?? 'Клац';
$activePage = $activePage ?? '';
$navItems = [
    'home' => ['index.php', 'Головна'],
    'order' => ['order.php', 'Нове замовлення'],
    'orders' => ['orders.php', 'Замовлення'],
];
?>
<!DOCTYPE html>
<html lang="uk">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle) ?> — Клац</title>
  <link rel="icon" href="assets/img/logo.svg" type="image/svg+xml">
  <link rel="stylesheet" href="assets/css/output.css">
</head>
<body class="bg-klats-50 text-slate-800 min-h-screen flex flex-col">
  <header class="bg-white shadow-sm sticky top-0 z-10">
    <div class="max-w-6xl 3xl:max-w-7xl mx-auto flex flex-col md:flex-row md:items-center md:justify-between gap-3 p-4">
      <a href="index.php" class="flex items-center gap-3">
        <img src="assets/img/logo.svg" alt="Логотип Клац" class="w-10 h-10">
        <span class="font-display text-2xl font-bold text-klats-700">Клац</span>
      </a>
      <nav class="flex flex-wrap gap-1 text-sm font-medium">
        <?php foreach ($navItems as $key => [$href, $label]): ?>
          <a href="<?= $href ?>" class="nav-link<?= $key === $activePage ? ' bg-klats-100 text-klats-700' : '' ?>"><?= $label ?></a>
        <?php endforeach; ?>
      </nav>
    </div>
  </header>
  <main class="flex-1 max-w-6xl 3xl:max-w-7xl w-full mx-auto px-4 py-8">

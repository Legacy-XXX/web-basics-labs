  </main>
  <footer class="bg-klats-900 text-klats-100">
    <div class="max-w-6xl 3xl:max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-3 gap-6 p-6 md:p-10 text-sm">
      <div>
        <p class="font-display text-xl font-bold text-white">Клац</p>
        <p class="mt-2 leading-normal">Інтернет-магазин комп'ютерної периферії.</p>
      </div>
      <div>
        <p class="font-semibold text-white mb-2">Контакти</p>
        <p>📞 <a href="tel:+380441234567" class="hover:text-zap-400">+38 (044) 123-45-67</a></p>
        <p>✉️ <a href="mailto:hello@klats.ua" class="hover:text-zap-400">hello@klats.ua</a></p>
      </div>
      <div>
        <p class="font-semibold text-white mb-2">Адреса</p>
        <p>м. Київ, вул. Хрещатик, 1</p>
        <p>Пн–Нд: 09:00–21:00</p>
      </div>
    </div>
    <p class="text-center text-xs text-klats-200 pb-6">© <?= date('Y') ?> Клац · Лабораторна робота №7 · Майдан Р. В., ІПЗм-41</p>
  </footer>
  <?php foreach ($scripts ?? [] as $script): ?>
    <script src="<?= e($script) ?>"></script>
  <?php endforeach; ?>
</body>
</html>

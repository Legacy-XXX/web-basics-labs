'use strict';

// Видалення запису: перехоплюємо відправку форми та надсилаємо запит з HTTP-методом DELETE
document.querySelectorAll('.delete-form').forEach((form) => {
  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    const id = form.querySelector('input[name="id"]').value;

    if (!confirm(`Видалити замовлення №${id}?`)) {
      return;
    }

    try {
      const response = await fetch('delete.php', {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
        body: JSON.stringify({ id: Number(id) }),
      });
      const result = await response.json();
      if (!response.ok || !result.success) {
        throw new Error(result.message);
      }
      window.location.href = `list.php?deleted=${id}`;
    } catch (error) {
      alert(`Не вдалося видалити: ${error.message}`);
    }
  });
});

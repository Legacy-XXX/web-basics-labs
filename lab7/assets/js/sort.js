'use strict';

// Перемикання сортування: формуємо URL з параметрами sort і order та робимо редирект
const sortField = document.getElementById('sort-field');
const sortOrder = document.getElementById('sort-order');

function goToSorted(sort, order) {
  const params = new URLSearchParams({ sort, order });
  window.location.href = `orders.php?${params.toString()}`;
}

if (sortField && sortOrder) {
  sortField.addEventListener('change', () => {
    goToSorted(sortField.value, sortOrder.dataset.order);
  });

  sortOrder.addEventListener('click', () => {
    const nextOrder = sortOrder.dataset.order === 'asc' ? 'desc' : 'asc';
    goToSorted(sortField.value, nextOrder);
  });
}

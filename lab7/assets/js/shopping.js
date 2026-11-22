'use strict';

// Передвизначений список зображень для випадаючого списку
const PRODUCT_TYPES = {
  mouse: { label: 'Миша', image: 'assets/img/mouse.svg' },
  keyboard: { label: 'Клавіатура', image: 'assets/img/keyboard.svg' },
  headphones: { label: 'Навушники', image: 'assets/img/headphones.svg' },
  monitor: { label: 'Монітор', image: 'assets/img/monitor.svg' },
  mousepad: { label: 'Килимок', image: 'assets/img/mousepad.svg' },
  webcam: { label: 'Вебкамера', image: 'assets/img/webcam.svg' },
};

// Масив доданих покупок (стан застосунку)
let purchases = [];
let nextId = 1;

// Посилання на DOM-елементи
const form = document.getElementById('purchase-form');
const nameInput = document.getElementById('purchase-name');
const priceInput = document.getElementById('purchase-price');
const typeSelect = document.getElementById('purchase-type');
const errorBox = document.getElementById('form-error');
const list = document.getElementById('purchase-list');
const emptyMessage = document.getElementById('empty-message');
const statCount = document.getElementById('stat-count');
const statTotal = document.getElementById('stat-total');

const formatPrice = (value) =>
  value.toLocaleString('uk-UA', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ₴';

// Заповнюємо select опціями з константи PRODUCT_TYPES
function fillTypeSelect() {
  for (const [value, type] of Object.entries(PRODUCT_TYPES)) {
    const option = document.createElement('option');
    option.value = value;
    option.textContent = type.label;
    typeSelect.appendChild(option);
  }
}

function showError(message) {
  errorBox.textContent = message;
  errorBox.classList.remove('hidden');
}

function hideError() {
  errorBox.textContent = '';
  errorBox.classList.add('hidden');
}

// Перевірка введених даних
function validate(name, price) {
  if (name === '') {
    return 'Введіть назву покупки.';
  }
  if (Number.isNaN(price) || price <= 0) {
    return 'Вартість має бути додатним числом.';
  }
  return null;
}

// Створення HTML-блоку для однієї покупки
function createPurchaseElement(purchase) {
  const type = PRODUCT_TYPES[purchase.type];
  const item = document.createElement('li');
  item.dataset.id = purchase.id;
  item.className = 'flex items-center gap-3 rounded-xl border border-klats-100 bg-klats-50 p-3';

  const img = document.createElement('img');
  img.src = type.image;
  img.alt = type.label;
  img.className = 'w-16 h-12 rounded-lg object-cover';

  const info = document.createElement('div');
  info.className = 'flex-1 min-w-0';
  const title = document.createElement('p');
  title.className = 'font-semibold text-klats-900 break-words';
  title.textContent = purchase.name;
  const meta = document.createElement('p');
  meta.className = 'text-sm text-slate-500';
  meta.textContent = `${type.label} · ${formatPrice(purchase.price)}`;
  info.append(title, meta);

  const deleteButton = document.createElement('button');
  deleteButton.type = 'button';
  deleteButton.dataset.action = 'delete';
  deleteButton.className = 'text-sm bg-red-500 text-white px-3 py-1.5 rounded-lg hover:bg-red-700 transition';
  deleteButton.textContent = 'Видалити';

  item.append(img, info, deleteButton);
  return item;
}

// Оновлення інформаційного блоку: кількість і загальна вартість
function updateSummary() {
  const total = purchases.reduce((sum, p) => sum + p.price, 0);
  statCount.textContent = purchases.length;
  statTotal.textContent = formatPrice(total);
  emptyMessage.classList.toggle('hidden', purchases.length > 0);
}

// Обробник відправки форми
form.addEventListener('submit', (event) => {
  event.preventDefault();

  const name = nameInput.value.trim();
  const price = parseFloat(priceInput.value);
  const type = typeSelect.value;

  const error = validate(name, price);
  if (error) {
    showError(error);
    return;
  }
  hideError();

  const purchase = { id: nextId++, name, price, type };
  purchases.push(purchase);
  list.appendChild(createPurchaseElement(purchase));
  updateSummary();

  // Очищаємо текстові поля після відправки
  nameInput.value = '';
  priceInput.value = '';
  nameInput.focus();
});

// Видалення блоку: один обробник на весь список (делегування подій)
list.addEventListener('click', (event) => {
  const button = event.target.closest('[data-action="delete"]');
  if (!button) return;

  const item = button.closest('li');
  const id = Number(item.dataset.id);
  purchases = purchases.filter((p) => p.id !== id);
  item.remove();
  updateSummary();
});

fillTypeSelect();
updateSummary();

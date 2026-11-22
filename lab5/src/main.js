import { Car, Truck, Motorcycle, Vehicle } from './Vehicle.js';
import { ParkingLot, formatDuration } from './ParkingLot.js';

// Імітація годинника, щоб показати тривалість паркування без очікування
let now = new Date('2026-11-22T09:00:00');
const clock = () => new Date(now);
const wait = (minutes) => {
  now = new Date(now.getTime() + minutes * 60000);
  console.log(`  ⏱ Минуло ${formatDuration(minutes)}, зараз ${time(now)}`);
};
const time = (date) => date.toTimeString().slice(0, 5);

// Виконання дії з перехопленням помилок
function attempt(description, action) {
  try {
    action();
  } catch (error) {
    console.log(`  ❌ ${description}: ${error.name}: ${error.message}`);
  }
}

function printFreeSpots(lot) {
  const free = lot.checkFreeSpots();
  console.log(`  Вільних місць: ${free.length} → ${free.map((s) => `№${s.spotNumber}(${s.size === 'car' ? 'авто' : 'мото'})`).join(', ') || 'немає'}`);
}

function printStatus(lot) {
  lot.spots.forEach((spot) => console.log(`  ${spot}`));
}

// Парковка: 2 місця для мотоциклів (№1–2) і 6 місць для авто (№3–8)
const lot = new ParkingLot({ motorcycle: 2, car: 6 }, clock);

console.log('=== Сценарій 1. Порожня парковка ===');
printFreeSpots(lot);

console.log('\n=== Сценарій 2. Паркування різних типів транспорту ===');
const car = new Car('AA1234BB', 480);
const truck = new Truck('BC5678CE', 7.5);
const moto1 = new Motorcycle('KA0001AB');
const moto2 = new Motorcycle('AI7777KM');
const moto3 = new Motorcycle('BH2025OO');

const car2 = new Car('AE4321IT', 350);

car.park(lot);
truck.park(lot);
moto1.park(lot);
attempt('Легковик на мото-місце', () => lot.parkVehicleAt(new Car('AM1111MA'), 2));
moto2.park(lot);
console.log('  Мото-місця закінчились — третій мотоцикл стає на місце для авто:');
moto3.park(lot);
car2.park(lot);
printFreeSpots(lot);

console.log('\n=== Сценарій 3. Перевірка помилок ===');
attempt('Повторне паркування', () => car.park(lot));
attempt('Паркування на зайняте місце', () => lot.parkVehicleAt(new Car('AM1111MA'), 3));
attempt('Вантажівка на одне конкретне місце', () => lot.parkVehicleAt(new Truck('KA9090EE', 12), 8));
attempt('Некоректний номер', () => new Car('12345'));
attempt('Створення абстрактного Vehicle', () => new Vehicle('AA0000AA', 'car'));
attempt('Вантажівка без двох сусідніх місць', () => new Truck('KA9090EE', 12).park(lot));
attempt('Виїзд невідомого авто', () => lot.leaveSpot('XX0000XX'));

console.log('\n=== Сценарій 4. Тривалість паркування та звільнення місць ===');
wait(45);
console.log(`  ${car} стоїть уже ${formatDuration(lot.getParkingTime(car.licensePlate))}`);
wait(90);
const carLeft = lot.leaveSpot(car.licensePlate);
console.log(`  ✅ ${carLeft.vehicle} виїхав з місця №${carLeft.spots.join(', ')}: ${time(carLeft.parkedAt)}–${time(carLeft.leftAt)}, ${formatDuration(carLeft.durationMinutes)}`);
wait(30);
const truckLeft = lot.leaveSpot(truck.licensePlate);
console.log(`  ✅ ${truckLeft.vehicle} — звільнено місця №${truckLeft.spots.join(' і №')}: ${time(truckLeft.parkedAt)}–${time(truckLeft.leftAt)}, ${formatDuration(truckLeft.durationMinutes)}`);
printFreeSpots(lot);

console.log('\n=== Сценарій 5. Нова вантажівка після звільнення місць ===');
attempt('Вантажівка KA9090EE', () => new Truck('KA9090EE', 12).park(lot));
attempt('Звільнення вже вільного місця', () => lot.spots[7].freeSpot());

console.log('\n=== Підсумковий стан парковки ===');
printStatus(lot);

console.log('\n=== Історія виїздів ===');
lot.history.forEach((r) =>
  console.log(`  ${r.vehicle.licensePlate}: місця №${r.spots.join(', ')}, ${time(r.parkedAt)}–${time(r.leftAt)} (${formatDuration(r.durationMinutes)})`)
);

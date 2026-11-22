import { ParkingError } from './ParkingError.js';
import { ParkingSpot } from './ParkingSpot.js';

// Форматування тривалості у вигляді «2 год 15 хв»
export function formatDuration(minutes) {
  const h = Math.floor(minutes / 60);
  const m = minutes % 60;
  return h > 0 ? `${h} год ${m} хв` : `${m} хв`;
}

// Парковка: керує списком місць, паркуванням і звільненням
export class ParkingLot {
  #spots;
  #tickets = new Map(); // номер авто → { vehicle, spots, parkedAt }
  #history = [];
  #clock;

  // layout: { motorcycle: 2, car: 6 }; clock — функція, що повертає поточний час
  constructor(layout, clock = () => new Date()) {
    this.#clock = clock;
    this.#spots = [];
    let number = 1;
    for (const [size, count] of Object.entries(layout)) {
      for (let i = 0; i < count; i++) {
        this.#spots.push(new ParkingSpot(number++, size));
      }
    }
  }

  // Автоматичний вибір місця з урахуванням розміру транспортного засобу
  parkVehicle(vehicle) {
    this.#ensureNotParked(vehicle);
    const spots = this.#findSpotsFor(vehicle);
    if (spots.length === 0) {
      throw new ParkingError(`Немає вільних місць для: ${vehicle}`);
    }
    return this.#occupy(vehicle, spots);
  }

  // Паркування на конкретне місце (з перевіркою, чи воно вільне й підходить)
  parkVehicleAt(vehicle, spotNumber) {
    this.#ensureNotParked(vehicle);
    if (vehicle.requiredSpots !== 1) {
      throw new ParkingError(`${vehicle} займає ${vehicle.requiredSpots} місця — оберіть автоматичне паркування`);
    }
    const spot = this.#getSpot(spotNumber);
    return this.#occupy(vehicle, [spot]);
  }

  // Звільнення місць і розрахунок тривалості перебування
  leaveSpot(licensePlate) {
    const plate = String(licensePlate).toUpperCase();
    const ticket = this.#tickets.get(plate);
    if (!ticket) {
      throw new ParkingError(`Транспортний засіб ${plate} не знайдено на парковці`);
    }
    ticket.spots.forEach((spot) => spot.freeSpot());
    this.#tickets.delete(plate);

    const leftAt = this.#clock();
    const durationMinutes = Math.round((leftAt - ticket.parkedAt) / 60000);
    const record = {
      vehicle: ticket.vehicle,
      spots: ticket.spots.map((s) => s.spotNumber),
      parkedAt: ticket.parkedAt,
      leftAt,
      durationMinutes,
    };
    this.#history.push(record);
    return record;
  }

  // Перелік вільних місць
  checkFreeSpots() {
    return this.#spots.filter((spot) => !spot.isOccupied);
  }

  // Скільки часу транспортний засіб уже стоїть на парковці
  getParkingTime(licensePlate) {
    const ticket = this.#tickets.get(String(licensePlate).toUpperCase());
    if (!ticket) {
      throw new ParkingError(`Транспортний засіб ${licensePlate} не знайдено на парковці`);
    }
    return Math.round((this.#clock() - ticket.parkedAt) / 60000);
  }

  get spots() {
    return [...this.#spots];
  }

  get history() {
    return [...this.#history];
  }

  #ensureNotParked(vehicle) {
    if (this.#tickets.has(vehicle.licensePlate)) {
      throw new ParkingError(`${vehicle} вже стоїть на парковці`);
    }
  }

  #getSpot(spotNumber) {
    const spot = this.#spots.find((s) => s.spotNumber === spotNumber);
    if (!spot) {
      throw new ParkingError(`Місця №${spotNumber} не існує`);
    }
    return spot;
  }

  #findSpotsFor(vehicle) {
    const free = this.checkFreeSpots().filter((spot) => vehicle.canUseSpot(spot));

    if (vehicle.requiredSpots === 1) {
      // Спочатку найменше відповідне місце (мотоцикл — на мото-місце)
      const order = { motorcycle: 0, car: 1 };
      free.sort((a, b) => order[a.size] - order[b.size] || a.spotNumber - b.spotNumber);
      return free.slice(0, 1);
    }

    // Кілька місць поспіль (вантажівка — два сусідні авто-місця)
    for (let i = 0; i + vehicle.requiredSpots <= free.length; i++) {
      const group = free.slice(i, i + vehicle.requiredSpots);
      const consecutive = group.every((s, k) => k === 0 || s.spotNumber === group[k - 1].spotNumber + 1);
      if (consecutive) return group;
    }
    return [];
  }

  #occupy(vehicle, spots) {
    spots.forEach((spot) => spot.assignVehicle(vehicle));
    this.#tickets.set(vehicle.licensePlate, { vehicle, spots, parkedAt: this.#clock() });
    return spots.map((s) => s.spotNumber);
  }
}

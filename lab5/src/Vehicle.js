import { ParkingError } from './ParkingError.js';

// Формат українського номерного знака: дві літери, чотири цифри, дві літери (AA1234BB)
const PLATE_PATTERN = /^[A-ZА-ЯІЇЄ]{2}\d{4}[A-ZА-ЯІЇЄ]{2}$/u;

// Базовий (абстрактний) клас транспортного засобу
export class Vehicle {
  #licensePlate;
  #size;

  constructor(licensePlate, size) {
    if (new.target === Vehicle) {
      throw new TypeError('Vehicle — абстрактний клас, створіть Car, Truck або Motorcycle');
    }
    const plate = String(licensePlate).trim().toUpperCase();
    if (!PLATE_PATTERN.test(plate)) {
      throw new ParkingError(`Некоректний номерний знак «${licensePlate}» (очікується формат AA1234BB)`);
    }
    this.#licensePlate = plate;
    this.#size = size;
  }

  get licensePlate() {
    return this.#licensePlate;
  }

  get size() {
    return this.#size;
  }

  // Скільки місць потрібно транспортному засобу (за замовчуванням — одне)
  get requiredSpots() {
    return 1;
  }

  // Чи може транспортний засіб стати на це місце (перевизначається в нащадках)
  canUseSpot(spot) {
    return spot.size === this.size;
  }

  get typeName() {
    return 'Транспортний засіб';
  }

  // Паркування: спільна логіка, яку нащадки доповнюють (поліморфізм)
  park(parkingLot) {
    return parkingLot.parkVehicle(this);
  }

  toString() {
    return `${this.typeName} ${this.licensePlate}`;
  }
}

// Легковий автомобіль: займає одне місце для автомобілів
export class Car extends Vehicle {
  #trunkSize;

  constructor(licensePlate, trunkSize = 400) {
    super(licensePlate, 'car');
    this.#trunkSize = trunkSize;
  }

  get trunkSize() {
    return this.#trunkSize;
  }

  get typeName() {
    return 'Легковий автомобіль';
  }

  park(parkingLot) {
    const spots = super.park(parkingLot);
    console.log(`  🚗 ${this} (багажник ${this.trunkSize} л) → місце №${spots.join(', ')}`);
    return spots;
  }
}

// Вантажівка: займає два сусідні місця для автомобілів
export class Truck extends Vehicle {
  #cargoWeight;

  constructor(licensePlate, cargoWeight) {
    super(licensePlate, 'truck');
    if (!(cargoWeight > 0)) {
      throw new ParkingError('Вантажопідйомність вантажівки має бути додатним числом');
    }
    this.#cargoWeight = cargoWeight;
  }

  get cargoWeight() {
    return this.#cargoWeight;
  }

  get requiredSpots() {
    return 2;
  }

  canUseSpot(spot) {
    return spot.size === 'car';
  }

  get typeName() {
    return 'Вантажівка';
  }

  park(parkingLot) {
    const spots = super.park(parkingLot);
    console.log(`  🚚 ${this} (${this.cargoWeight} т) → місця №${spots.join(' і №')}`);
    return spots;
  }
}

// Мотоцикл: може стати на місце для мотоциклів або для автомобілів
export class Motorcycle extends Vehicle {
  constructor(licensePlate) {
    super(licensePlate, 'motorcycle');
  }

  canUseSpot(spot) {
    return spot.size === 'motorcycle' || spot.size === 'car';
  }

  get typeName() {
    return 'Мотоцикл';
  }

  park(parkingLot) {
    const spots = super.park(parkingLot);
    console.log(`  🏍️ ${this} → місце №${spots.join(', ')}`);
    return spots;
  }
}

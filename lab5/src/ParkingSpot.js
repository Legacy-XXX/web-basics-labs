import { ParkingError } from './ParkingError.js';

const SIZE_NAMES = { motorcycle: 'мото', car: 'авто' };

// Паркувальне місце: номер, розмір і стан
export class ParkingSpot {
  #spotNumber;
  #size;
  #isOccupied = false;
  #vehicle = null;

  constructor(spotNumber, size) {
    if (!Object.hasOwn(SIZE_NAMES, size)) {
      throw new ParkingError(`Невідомий розмір місця: ${size}`);
    }
    this.#spotNumber = spotNumber;
    this.#size = size;
  }

  get spotNumber() {
    return this.#spotNumber;
  }

  get size() {
    return this.#size;
  }

  get isOccupied() {
    return this.#isOccupied;
  }

  get vehicle() {
    return this.#vehicle;
  }

  assignVehicle(vehicle) {
    if (this.#isOccupied) {
      throw new ParkingError(`Місце №${this.#spotNumber} вже зайняте (${this.#vehicle})`);
    }
    if (!vehicle.canUseSpot(this)) {
      throw new ParkingError(
        `${vehicle} не може стати на місце №${this.#spotNumber} (розмір: ${SIZE_NAMES[this.#size]})`
      );
    }
    this.#vehicle = vehicle;
    this.#isOccupied = true;
  }

  freeSpot() {
    if (!this.#isOccupied) {
      throw new ParkingError(`Місце №${this.#spotNumber} і так вільне`);
    }
    const vehicle = this.#vehicle;
    this.#vehicle = null;
    this.#isOccupied = false;
    return vehicle;
  }

  toString() {
    const state = this.#isOccupied ? `зайняте: ${this.#vehicle.licensePlate}` : 'вільне';
    return `№${this.#spotNumber} [${SIZE_NAMES[this.#size]}] — ${state}`;
  }
}

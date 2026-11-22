// Власний тип помилки для некоректних операцій на парковці
export class ParkingError extends Error {
  constructor(message) {
    super(message);
    this.name = 'ParkingError';
  }
}

<?php
declare(strict_types=1);

// Обробка форми замовлення: збір даних → валідація → збереження
class FormHandler
{
    private OrderValidator $validator;
    private OrderModel $model;

    public function __construct()
    {
        $this->validator = new OrderValidator();
        $this->model = new OrderModel();
    }

    /**
     * @return array{success: bool, id: ?int, errors: array, data: array}
     */
    public function handle(array $input, ?int $id = null): array
    {
        $data = OrderFactory::normalize($input);
        $errors = $this->validator->validate($data);

        if ($errors) {
            return ['success' => false, 'id' => $id, 'errors' => $errors, 'data' => $data];
        }

        $order = OrderFactory::fromInput($data, $id);
        return ['success' => true, 'id' => $this->save($order), 'errors' => [], 'data' => $data];
    }

    // Новий запис — INSERT, існуючий — UPDATE
    public function save(Order $order): int
    {
        if ($order->getId() === null) {
            return $this->model->insert($order);
        }
        $this->model->update($order);
        return $order->getId();
    }
}

<?php
declare(strict_types=1);

// Створення замовлення: показ форми (GET) і обробка (POST)
class CreateAction extends Controller
{
    public function handle(): void
    {
        $data = [];
        $errors = [];

        if ($this->request->method() === 'POST') {
            $result = (new FormHandler())->handle($this->request->all());
            if ($result['success']) {
                $this->redirect('list.php?created=' . $result['id']);
            }
            $data = $result['data'];
            $errors = $result['errors'];
        }

        $this->view->render('orders/form', [
            'mode' => 'create',
            'id' => null,
            'data' => $data,
            'errors' => $errors,
        ], 'Нове замовлення', 'create');
    }
}

<?php
declare(strict_types=1);

// Редагування: форма з попередньо заповненими полями (GET) і оновлення запису (POST)
class EditAction extends Controller
{
    public function handle(): void
    {
        $isPost = $this->request->method() === 'POST';
        $id = (int) ($isPost ? $this->request->input('id') : $this->request->query('id'));
        $existing = $id > 0 ? (new OrderModel())->findById($id) : null;

        if ($existing === null) {
            $this->notFound("Замовлення №{$id} не знайдено.");
            return;
        }

        $data = $existing->toFormData();
        $errors = [];

        if ($isPost) {
            $result = (new FormHandler())->handle($this->request->all(), $id);
            if ($result['success']) {
                $this->redirect('list.php?updated=' . $id);
            }
            $data = $result['data'];
            $errors = $result['errors'];
        }

        $this->view->render('orders/form', [
            'mode' => 'edit',
            'id' => $id,
            'data' => $data,
            'errors' => $errors,
            'createdAt' => $existing->getCreatedAt(),
        ], "Редагування замовлення №{$id}", 'list');
    }
}

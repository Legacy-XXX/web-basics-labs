<?php
declare(strict_types=1);

// Видалення: приймає HTTP-метод DELETE (fetch) або POST із полем _method=DELETE
class DeleteAction extends Controller
{
    public function handle(): void
    {
        if ($this->request->method() !== 'DELETE') {
            $this->json(['success' => false, 'message' => 'Дозволено лише метод DELETE'], 405);
        }

        $id = (int) $this->request->input('id', 0);
        $deleted = $id > 0 && (new OrderModel())->delete($id);

        if ($this->request->wantsJson()) {
            $deleted
                ? $this->json(['success' => true, 'message' => "Замовлення №{$id} видалено", 'id' => $id])
                : $this->json(['success' => false, 'message' => "Замовлення №{$id} не знайдено"], 404);
        }

        $deleted ? $this->redirect('list.php?deleted=' . $id) : $this->notFound("Замовлення №{$id} не знайдено.");
    }
}

<?php
declare(strict_types=1);

// Список замовлень із сортуванням і повідомленнями про результат дій
class ListAction extends Controller
{
    public function handle(): void
    {
        $sort = OrderModel::normalizeSort($this->request->query('sort'));
        $order = OrderModel::normalizeDirection($this->request->query('order'));
        $orders = (new OrderModel())->findAll($sort, $order);

        $flash = null;
        if ($id = (int) $this->request->query('created', 0)) {
            $flash = "✅ Замовлення №{$id} успішно додано.";
        } elseif ($id = (int) $this->request->query('updated', 0)) {
            $flash = "✏️ Замовлення №{$id} оновлено.";
        } elseif ($id = (int) $this->request->query('deleted', 0)) {
            $flash = "🗑️ Замовлення №{$id} видалено.";
        }

        $this->view->render('orders/list', [
            'orders' => $orders,
            'sort' => $sort,
            'order' => $order,
            'flash' => $flash,
            'scripts' => ['assets/js/sort.js', 'assets/js/delete.js'],
        ], 'Замовлення', 'list');
    }
}

<?php namespace App\Repositories\Order;

use App\Services\Order\OrderProtocol;

class EloquentClientOrderRepository implements ClientOrderRepositoryContract {

    public function createOrder($data)
    {

    }

    public function updateOrderStatus($order_no, $status)
    {
        // TODO: Implement updateOrderStatus() method.
    }

    public function updateOrderPayStatus($order_no, $status, $pay_channel = null)
    {
        // TODO: Implement updateOrderPayStatus() method.
    }

    public function getOrder($order_no)
    {
        // TODO: Implement getOrder() method.
    }

    public function getAllOrders($status, $order_by = 'created_at', $sort = 'desc')
    {
        // TODO: Implement getAllOrders() method.
    }

    public function getPaginatedOrders($status, $order_by = 'created_at', $sort = 'desc', $per_page = OrderProtocol::ORDER_PER_PAGE)
    {
        // TODO: Implement getPaginatedOrders() method.
    }
}

<?php namespace App\Repositories\Order;
use App\Services\Order\OrderProtocol;

interface AdminOrderRepositoryContract {

    public function updateOrderStatus($order_no, $status);

    public function getOrder($order_no);

    public function getAllOrders($status, $keyword, $order_by = 'created_at', $sort = 'desc');

    public function getPaginatedOrders($status, $keyword, $order_by = 'created_at', $sort = 'desc', $per_page = OrderProtocol::ORDER_PER_PAGE);

}

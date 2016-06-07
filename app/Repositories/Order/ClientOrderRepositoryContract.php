<?php namespace App\Repositories\Order;

use App\Services\Order\OrderProtocol;

interface ClientOrderRepositoryContract {

    public function createOrder($data);

    public function updateOrderStatus($order_no, $status);

    public function updateOrderPayStatus($order_no, $status, $pay_channel = null);

    public function getOrder($order_no);

    public function getAllOrders($status, $order_by = 'created_at', $sort = 'desc');

    public function getPaginatedOrders($status, $order_by = 'created_at', $sort = 'desc', $per_page = OrderProtocol::ORDER_PER_PAGE);

}

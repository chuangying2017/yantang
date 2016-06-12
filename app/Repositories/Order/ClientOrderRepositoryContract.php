<?php namespace App\Repositories\Order;

use App\Services\Order\OrderProtocol;

interface ClientOrderRepositoryContract {

    public function createOrder($data);

    public function updateOrderStatus($order_id, $status);

    public function updateOrderPayStatus($order_id, $status, $pay_channel = null);

    public function updateOrderStatusAsPaid($order_id, $pay_channel);

    public function updateOrderStatusAsDeliver($order_id);

    public function updateOrderStatusAsDone($order_id);

    public function updateOrderStatusAsCancel($order_id);

    public function getOrder($order_no, $with_detail = false);

    public function getAllOrders($status = null, $order_by = 'created_at', $sort = 'desc');

    public function getPaginatedOrders($status = null, $order_by = 'created_at', $sort = 'desc', $per_page = OrderProtocol::ORDER_PER_PAGE);

}

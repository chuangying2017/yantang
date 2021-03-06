<?php namespace App\Services\Order;
interface OrderManageContract {

    public function orderCancel($order_id, $memo = '', $order_skus_info = null);

    public function cancelUnpaidOrder($order_id);

    public function orderPaid($order_id);

    public function orderDeliver($order_id, $company, $express_no);

    public function orderDone($order_id);

    public function orderIsFirstPaid($order);
}

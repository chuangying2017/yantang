<?php namespace App\Services\Order;
interface OrderManageContract {

    public function orderCancel($order_id, $memo);

    public function orderPaid($order_id);

    public function orderDeliver($order_id, $company, $express_no);

    public function orderDone($order_id);

}

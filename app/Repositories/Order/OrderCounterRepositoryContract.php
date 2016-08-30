<?php namespace App\Repositories\Order;
interface OrderCounterRepositoryContract {

    public function getOrdersCount($user_id, $order_type, $status = null, $start_time = null);

    public function hasOrdersCount($user_id, $order_type, $status = null, $start_time = null);

}

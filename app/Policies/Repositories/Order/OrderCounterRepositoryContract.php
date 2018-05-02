<?php namespace App\Repositories\Order;
interface OrderCounterRepositoryContract {

    public function getFirstPaidOrder($user_id, $order_type, $start_time = null);

    
}

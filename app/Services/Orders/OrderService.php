<?php namespace App\Services\Orders;

class OrderService {

    public static function show($order_id)
    {
        $order = OrderRepository::queryFullOrder($order_id);

        return $order;
    }

}

<?php namespace App\Services\Orders;

class OrderService {

    public static function authOrder($user_id, $order_no)
    {
        $order = OrderRepository::queryOrderByOrderNo($order_no);

        return ($order->user_id == $user_id) ? $order : false;

    }

    public static function show($user_id, $order_no)
    {
        $order = self::authOrder($user_id, $order_no);
        if ( ! $order) {
            throw new \Exception('非法请求');
        }
        $order = OrderRepository::queryFullOrder($order);

        return $order;
    }

    public static function lists($user_id)
    {
        return OrderRepository::lists($user_id);
    }

}

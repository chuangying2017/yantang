<?php namespace App\Services\Orders;

use App\Services\Orders\Exceptions\OrderAuthFail;

class OrderService {

    public static function authOrder($user_id, $order_no)
    {
        $order = OrderRepository::queryOrderByOrderNo($order_no);
        if ($order->user_id == $user_id) {
            return $order;
        }

        throw new OrderAuthFail();
    }

    public static function show($user_id, $order_no)
    {
        $order = self::authOrder($user_id, $order_no);
        $order = OrderRepository::queryFullOrder($order);

        return $order;
    }

    public static function lists($user_id)
    {
        return OrderRepository::lists($user_id);
    }



    public static function delete($user_id, $order_no)
    {
        try {
            $order = self::authOrder($user_id, $order_no);
            OrderProtocol::validStatus($order['status'], OrderProtocol::STATUS_OF_CANCEL);

            if ($order['status'] == OrderProtocol::STATUS_OF_PAID) {
                #todo @troy Order Refund
            }

            OrderRepository::deleteOrder($order);
        } catch (\Exception $e) {
            throw $e;
        }

    }



}

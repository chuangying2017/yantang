<?php namespace App\Services\Orders;

class OrderService {

    public static function authOrder($user_id, $order_no)
    {
        $order = OrderRepository::queryOrderByOrderNo($order_no);
        if ($order->user_id == $user_id) {
            return $order;
        }

        throw new \Exception('非法请求');
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

    public static function orderNeedPay($user_id, $order_no)
    {
        $order = self::authOrder($user_id, $order_no);

        //检查订单状态是否需要支付
        if ($order_no['status'] == OrderProtocol::STATUS_OF_UNPAID) {
            #todo 检查主订单是否为同步
            return true;
        }

        return false;
    }


}

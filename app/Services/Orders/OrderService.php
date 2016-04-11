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

    public static function authChildOrder($user_id, $order_no)
    {
        $order = OrderRepository::queryChildOrderByOrderNo($order_no);
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

    public static function lists($user_id = null, $status = null, $sort_by = 'created_at', $sort_type = 'desc', $relation = ['children', 'address'], $paginate = 20)
    {
        return OrderRepository::lists($user_id, $sort_by, $sort_type, $relation, $status, $paginate);
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

    /**
     * 完成订单
     * @param $user_id
     * @param $order_no
     * @throws \Exception
     */
    public static function done($user_id, $order_no)
    {
        try {

//            $order = self::authOrder($user_id, $order_no);
            $order = self::authChildOrder($user_id, $order_no);
            OrderProtocol::validStatus($order['status'], OrderProtocol::STATUS_OF_DONE);

            OrderRepository::updateChildOrderAsDone($order_no);
        } catch (\Exception $e) {
            throw $e;
        }

    }


}

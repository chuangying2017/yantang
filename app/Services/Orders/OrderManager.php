<?php namespace App\Services\Orders;

class OrderManager {

    public static function lists($user_id = null, $sort_by = 'created_at', $sort_type = 'desc', $relation = null, $status = null, $paginate = null, $merchant_id)
    {
        return OrderRepository::lists($user_id, $sort_by, $sort_type, $relation, $status, $paginate, $merchant_id);
    }

    public static function show($order_no)
    {
        $order = OrderRepository::queryFullOrder($order_no);

        return $order;
    }

    public static function delete($order_no)
    {
        try {
            $order = OrderRepository::queryOrderByOrderNo($order_no);
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

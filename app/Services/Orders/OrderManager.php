<?php namespace App\Services\Orders;

class OrderManager {

    public static function lists($user_id = null, $sort_by = 'created_at', $sort_type = 'desc', $relation = 'children', $status = null, $paginate = null)
    {
        return OrderRepository::lists($user_id, $sort_by, $sort_type, $relation, $status, $paginate);
    }

    public static function show($order_no)
    {
        $order = OrderRepository::queryFullOrder($order_no);

        return $order;
    }

}

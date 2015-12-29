<?php namespace App\Services\Orders;

class OrderDeliver {

    public static function deliverChildOrders($child_order_id, $deliver_id)
    {
        $child_order_ids = to_array($child_order_id);

        OrderRepository::updateChildOrderAsDeliver($child_order_ids, $deliver_id);
    }

    public static function deliver($company_id, $post_no, $order_id, $child_order_id = null)
    {
        $deliver = OrderRepository::orderDeliver($company_id, $post_no, $order_id);

        if (is_null($child_order_id)) {
            OrderRepository::updateChildOrderAsDeliverByOrder($order_id, $deliver['id']);
        } else {
            self::deliverChildOrders($child_order_id, $deliver['id']);
        }

        OrderRepository::updateOrderAsDeliver($order_id);
    }

}

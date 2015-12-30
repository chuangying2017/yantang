<?php namespace App\Services\Orders;

class OrderDeliver {

    public static function deliver($order_no, $company_name, $post_no)
    {

        if (self::isMainOrder($order_no)) {
            $order = OrderRepository::queryOrderByOrderNo($order_no);
        } else {
            $order = OrderRepository::queryChildOrderByOrderNo($order_no);
        }

        if ( ! self::checkOrderIsPaid($order)) {
            throw new \Exception('订单:' . $order_no . ' 未支付,发货失败');
        }

        $deliver = OrderRepository::createOrderDeliver($company_name, $post_no);
        $deliver_id = $deliver['id'];

        if (self::isMainOrder($order_no)) {
            $count = OrderRepository::updateChildOrderAsDeliverByOrder($order['id'], $deliver_id);
        } else {
            $count = OrderRepository::updateChildOrderAsDeliver($order_no, $deliver_id);
        }


        if ( ! $count) {
            throw new \Exception('发货订单号:' . $order_no . ' 不存在');
        }

        return $count;
    }


    public static function checkOrderIsPaid($order)
    {
        return OrderProtocol::statusIs(OrderProtocol::STATUS_OF_PAID, $order['status']);
    }


    public static function cancelDeliver($order_no)
    {
        $deliver_id = 0;

        if (self::isMainOrder($order_no)) {
            $order = OrderRepository::queryOrderByOrderNo($order_no);
            $count = OrderRepository::updateChildOrderAsDeliverByOrder($order['id'], $deliver_id);
        } else {
            $count = OrderRepository::updateChildOrderAsDeliver($order_no, $deliver_id);
        }

        if ( ! $count) {
            throw new \Exception('发货订单号:' . $order_no . ' 不存在');
        }

        return $count;
//        return OrderRepository::updateOrderAsUnDeliver($main_no);
    }


    public static function expressCompany()
    {
        return OrderRepository::expressCompany();
    }

    public static function isMainOrder($order_no)
    {
        return strlen($order_no) == OrderProtocol::MAIN_ORDER_LENGTH;
    }

}

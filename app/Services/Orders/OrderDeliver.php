<?php namespace App\Services\Orders;

class OrderDeliver {

    public static function deliver($order_no, $company_name, $post_no)
    {
        $deliver = OrderRepository::createOrderDeliver($company_name, $post_no);
        $deliver_id = $deliver['id'];

        if (strlen($order_no) == OrderProtocol::MAIN_ORDER_LENGTH) {
            $count = OrderRepository::updateChildOrderAsDeliverByOrder($order_no, $deliver_id);
        } else {
            $count = OrderRepository::updateChildOrderAsDeliver($order_no, $deliver_id);
        }

        if ( ! $count) {
            throw new \Exception('发货订单号:' . $order_no . ' 不存在');
        }

        return $count;
//        return OrderRepository::updateOrderAsDeliver($main_no);
    }


    public static function cancelDeliver($order_no)
    {
        $deliver_id = 0;


        if (strlen($order_no) == OrderProtocol::MAIN_ORDER_LENGTH) {
            $count = OrderRepository::updateChildOrderAsDeliverByOrder($order_no, $deliver_id);
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

}

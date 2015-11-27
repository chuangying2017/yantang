<?php namespace App\Services\Orders\Payments;

use App\Services\Orders\OrderRepository;

class CheckOut {

    public function checkout($order_no)
    {
        //读取订单信息
        $order = OrderRepository::queryFullOrder($order_no);


    }

    public function generateMainPayment($amount)
    {

    }

}

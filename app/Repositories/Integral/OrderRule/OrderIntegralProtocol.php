<?php
namespace App\Repositories\Integral\OrderRule;

class OrderIntegralProtocol
{
    const ORDER_STATUS_DROPSHIP  =   'DropShip'; //待发货
    const ORDER_STATUS_DELIVERED = 'Delivered'; //已发货
    const ORDER_STATUS_REJECT = 'reject'; //已拒绝
    const ORDER_STATUS_CONFIRM = 'confirm'; //已确定

    public function order_generator() //生成订单号
    {
        return mt_rand(0,99).substr(date('Y'),-2) . date('mdHis') . mt_rand(0,99);
    }

    public function order_test()
    {
        return 'string';
    }
}
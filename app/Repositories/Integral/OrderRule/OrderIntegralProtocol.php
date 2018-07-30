<?php
namespace App\Repositories\Integral\OrderRule;

class OrderIntegralProtocol
{
    const ORDER_STATUS_DROPSHIP  =   'DropShip'; //待发货
    const ORDER_STATUS_DELIVERED = 'Delivered'; //已发货 对应 待收货
    const ORDER_STATUS_REJECT = 'reject'; //已拒绝
    const ORDER_STATUS_CONFIRM = 'confirm'; //已确定
    const ORDER_CHANNEL_PAY = 'integral';//支付通道
    const ORDER_YANTANG_INTEGRAL = '燕塘积分商城 - 导出:';

    const ORDER_STATUS_ARRAY_TIME = ['DropShip'=>'created_at','Delivered'=>'updated_at','reject'=>'deleted_at'];

    const TABLE_INTEGRAL_ORDER = ['order_no'];
    const ORDER_ADMIN_TABLE_SEARCH = [
        'integral_orders'   =>  ['DropShip' => ['created_at'], 'Delivered'=>['updated_at'], 'confirm' => ['updated_at']],
        'integral_orders_sku' => ['product_name']
    ];

    const ORDER_STATUS_ARRAY = ['DropShip' => '待发货','Delivered' => '待收货', 'reject' => '已拒绝' ,'confirm' => '已确认'];

    const ORDER_TYPE = ['integral' => '兑换'];
    public function order_generator() //生成订单号
    {
        return '10'.mt_rand(10,99).substr(date('Y'),-2) . date('md') . mt_rand(10,99) . substr(ceil(microtime(true)),-6);
    }

    public function order_test()
    {
        return 'string';
    }
}
<?php namespace App\Services\Orders;

class OrderProtocol {


    const STATUS_OF_UNPAID = 'unpaid';
    const STATUS_OF_PAID = 'paid';
    const STATUS_OF_DELIVER = 'deliver';
    const STATUS_OF_DONE = 'done';
    const STATUS_OF_REVIEW = 'review';
    const STATUS_OF_CANCEL = 'cancel';
    const STATUS_OF_REFUNDING = 'refunding';
    const STATUS_OF_REFUNDED = 'refunded';


    const TYPE_OF_DISCOUNT = 'discount';
    const TYPE_OF_MAIN = 'main';

    const RESOURCE_OF_TICKET = 'App\Models\Ticket';


    public static function status($key = null)
    {
        $message = [
            self::STATUS_OF_UNPAID    => '未支付',
            self::STATUS_OF_PAID      => '已支付',
            self::STATUS_OF_DELIVER   => '已发货',
            self::STATUS_OF_DONE      => '已完成',
            self::STATUS_OF_CANCEL    => '已取消',
            self::STATUS_OF_REVIEW    => '已评价',
            self::STATUS_OF_REFUNDING => '退款中',
            self::STATUS_OF_REFUNDED  => '已退款',
        ];

        return is_null($key) ? $message : $message[ $key ];
    }

}

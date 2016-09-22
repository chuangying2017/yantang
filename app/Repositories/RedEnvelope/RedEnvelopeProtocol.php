<?php namespace App\Repositories\RedEnvelope;

use App\Services\Order\OrderProtocol;

class RedEnvelopeProtocol {

    const PER_PAGE = 20;

    const RULE_STATUS_OF_OK = 1;
    const RULE_STATUS_OF_DRAFT = 0;

    const RECORD_STATUS_OF_OK = 1;
    const RECORD_STATUS_OF_CANCEL = 2;

    public static function status($key = null)
    {
        $data = [
            self::RULE_STATUS_OF_OK => '进行中',
            self::RULE_STATUS_OF_DRAFT => '待发布'
        ];

        return is_null($key) ? $data : array_get($data, $key, null);
    }

    const TYPE_OF_SUBSCRIBE_ORDER = 'preorder';

    public static function type($key = null)
    {
        $data = [
            self::TYPE_OF_SUBSCRIBE_ORDER => '订单红包',
        ];

        return is_null($key) ? $data : array_get($data, $key, null);
    }

    public static function typeOfOrder($order_type)
    {
        $data = [
            OrderProtocol::ORDER_TYPE_OF_SUBSCRIBE => self::TYPE_OF_SUBSCRIBE_ORDER
        ];

        return array_get($data, $order_type, null);
    }


}

<?php namespace App\Repositories\RedEnvelope;
class RedEnvelopeProtocol {

    const PER_PAGE = 20;

    const STATUS_OF_OK = 1;
    const STATUS_OF_DRAFT = 0;

    public static function status($key = null)
    {
        $data = [
            self::STATUS_OF_OK => '进行中',
            self::STATUS_OF_DRAFT => '待发布'
        ];

        return is_null($key) ? $data : array_get($data, $key, null);
    }


    const TYPE_OF_ORDER = 'order';

    public static function type($key = null)
    {
        $data = [
            self::TYPE_OF_ORDER => '订单红包',
        ];

        return is_null($key) ? $data : array_get($data, $key, null);
    }
    
    const RESOURCE_TYPE_OF_ORDER = 'order';
    
    


}

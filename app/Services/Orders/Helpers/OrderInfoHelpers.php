<?php namespace App\Services\Orders\Helpers;

use App\Services\Orders\OrderProtocol;
use App\Services\Orders\OrderRepository;
use Cache;
use Faker\Provider\Uuid;

trait OrderInfoHelpers {

    public static function setOrder($order_info)
    {
        $order_info['uuid'] = isset($order_info['uuid']) ? $order_info['uuid'] : Uuid::uuid();
        $uuid = $order_info['uuid'];
        if (Cache::has($uuid)) {
            Cache::forget($uuid);
        }
        Cache::put($uuid, $order_info, 60);
    }

    public static function getOrder($uuid, $done = null)
    {
        if ( ! is_null($done)) {
            return Cache::pull($uuid);
        }

        return Cache::get($uuid);
    }


}

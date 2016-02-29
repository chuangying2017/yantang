<?php namespace App\Services\Utilities;

use App\Models\Access\User\User;
use App\Models\Order;
use App\Services\Orders\OrderProtocol;
use Cache;
use Carbon\Carbon;

class DataHelper {

    const DATA_PREFIX = 'east_beauty_backend_info_';

    const CACHE_MINUTES = 5;

    public static function totalUserCount()
    {
        return Cache::remember(self::DATA_PREFIX . 'total_users_count', self::CACHE_MINUTES, function () {
            User::count();
        });
    }

    public static function todayUserCount()
    {
        return Cache::remember(self::DATA_PREFIX . 'today_users_count', self::CACHE_MINUTES, function () {
            User::where('created_at', '>=', Carbon::today())->count();
        });
    }

    public static function totalDealAmount()
    {
        return Cache::remember(self::DATA_PREFIX . 'total_deal_amount', self::CACHE_MINUTES, function () {
            Order::whereIn('status', [OrderProtocol::STATUS_OF_PAID, OrderProtocol::STATUS_OF_DELIVER, OrderProtocol::STATUS_OF_DONE]);
        });
    }

    public static function todayDealAmount()
    {
        return Cache::remember(self::DATA_PREFIX . 'today_deal_amount', self::CACHE_MINUTES, function () {
            Order::where('created_at', '>=', Carbon::today())->whereIn('status', [OrderProtocol::STATUS_OF_PAID, OrderProtocol::STATUS_OF_DELIVER, OrderProtocol::STATUS_OF_DONE]);
        });
    }

}

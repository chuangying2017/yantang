<?php namespace App\Services\Notify\Roles;

use App\Models\Subscribe\Station;
use App\Services\Socialite\ProviderSupportTrait;

class NotifyStation implements NotifyRoleContract {

    use ProviderSupportTrait;

    public static function getPhone($station_id)
    {
        return Station::query()->where('id', $station_id)->pluck('phone')->first();
    }

    public static function getWeixinOpenId($station_id)
    {
        $user_ids = \DB::table('station_user')->where('station_id', $station_id)->pluck('user_id');
        
        return self::getProviderId($user_ids);
    }
}

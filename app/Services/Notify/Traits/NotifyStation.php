<?php namespace App\Services\Notify\Traits;

use App\Models\Subscribe\Station;
use App\Services\Socialite\ProviderSupportTrait;

trait NotifyStation {

    use ProviderSupportTrait;

    public static function getPhone($station_id)
    {
        return Station::query()->find($station_id)->pluck('phone')->first();
    }

    public static function getOpenIds($station_id)
    {
        $user_ids = \DB::table('station_user')->where('station_id', $station_id)->pluck('user_id');
        return self::getProviderId($user_ids);
    }
}

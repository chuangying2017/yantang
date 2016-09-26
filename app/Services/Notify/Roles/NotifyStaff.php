<?php namespace App\Services\Notify\Roles;

use App\Models\Subscribe\StationStaff;
use App\Services\Socialite\ProviderSupportTrait;

class NotifyStaff implements NotifyRoleContract {

    use ProviderSupportTrait;

    public static function getPhone($staff_id)
    {
        return StationStaff::query()->where('id', $staff_id)->pluck('phone')->first();
    }

    public static function getWeixinOpenId($staff_id)
    {
        $user_id = StationStaff::query()->where('id', $staff_id)->pluck('user_id')->first();

        return self::getProviderId($user_id);
    }
}

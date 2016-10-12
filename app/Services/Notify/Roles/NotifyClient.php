<?php namespace App\Services\Notify\Roles;

use App\Models\Access\User\User;
use App\Services\Socialite\ProviderSupportTrait;

class NotifyClient implements NotifyRoleContract {

    use ProviderSupportTrait;

    public static function getPhone($user_id)
    {
        return User::query()->where('id', $user_id)->pluck('phone')->first();
    }

    public static function getWeixinOpenId($user_id)
    {
        return self::getProviderId($user_id);
    }
}

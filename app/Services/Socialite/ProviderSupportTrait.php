<?php namespace App\Services\Socialite;

use App\Models\Access\User\UserProvider;

trait ProviderSupportTrait {

    public static function getProviderId($user_id, $provider = 'weixin')
    {
        if (is_array($user_id)) {
            return UserProvider::query()->whereIn('user_id', $user_id)->where("provider", $provider)->pluck('provider_id')->all();
        }
        return UserProvider::query()->where('user_id', $user_id)->where("provider", $provider)->pluck('provider_id')->first();
    }

}

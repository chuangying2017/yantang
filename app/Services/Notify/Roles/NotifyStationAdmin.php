<?php namespace App\Services\Notify\Roles;

use App\Models\Access\User\User;
use App\Repositories\Backend\AccessProtocol;
use App\Services\Socialite\ProviderSupportTrait;

class NotifyStationAdmin implements NotifyRoleContract {

    use ProviderSupportTrait;

    public static function getPhone($id)
    {
        $user_ids = self::getRoleUsers();

        return User::query()->whereNotNull('phone')->whereIn('id', $user_ids)->pluck('phone')->first();
    }

    public static function getWeixinOpenId($id)
    {
        $user_ids = self::getRoleUsers();

        return self::getProviderId($user_ids);
    }

    protected static function getRoleUsers()
    {
        return \DB::table('assigned_roles')->where('role_id', AccessProtocol::ID_ROLE_OF_STATION_ADMIN)->pluck('user_id');
    }
}

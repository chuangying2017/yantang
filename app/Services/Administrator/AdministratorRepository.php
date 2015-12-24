<?php namespace App\Services\Administrator;

use App\Models\MerchantAdmin;

class AdministratorRepository {

    public static function createMerchantAdmin($user_id, $merchant_id, $name)
    {
        return MerchantAdmin::updateOrCreate(
            ['user_id' => $user_id],
            compact('user_id', 'merchant_id', 'name')
        );
    }

    public static function getByUser($user_id)
    {
        return MerchantAdmin::where('user_id', $user_id)->first();
    }

    public static function updateMerchantAdmin($user_id, $merchant_id, $name)
    {
        $admin = MerchantAdmin::where('user_id', $user_id)->firstOrFail();
        $admin->fill(compact('merchant_id', 'name'));
        $admin->save();

        return $admin;
    }

    public static function deleteMerchantAdmin($user_id)
    {
        return MerchantAdmin::where('user_id', $user_id)->delete();
    }

}

<?php namespace App\Services\Administrator;
class AdministratorService {

    public static function createMerchantAdmin($user_id, $merchant_id, $username)
    {
        return AdministratorRepository::createMerchantAdmin($user_id, $merchant_id, $username);
    }

    public static function updateMerchantAdmin($user_id, $merchant_id, $username)
    {
        return AdministratorRepository::updateMerchantAdmin($user_id, $merchant_id, $username);
    }

    public static function deleteMerchantAdmin($user_id)
    {
        return AdministratorRepository::deleteMerchantAdmin($user_id);
    }

}

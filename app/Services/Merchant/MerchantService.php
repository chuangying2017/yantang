<?php namespace App\Services\Merchant;

use App\Models\Access\Role\Role;
use App\Services\Administrator\AdministratorService;

/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 2/12/2015
 * Time: 5:33 PM
 */
class MerchantService {


    public static function getMerchantIdByUserId($user_id)
    {
        $merchant_admin = AdministratorService::userMerchantAdmin($user_id);
        if ($merchant_admin) {
            return $merchant_admin['merchant_id'];
        }

        throw new \Exception('当前用户不是商家管理员');
    }

    protected static function filterBaseMerchantData($data)
    {
        return array_only($data, ['name', 'avatar', 'phone', 'director', 'email']);
    }

    public function create($data)
    {
        try {
            //创建商家
            $merchant = MerchantRepository::create(self::filterBaseMerchantData($data));

            AdministratorService::createMerchantAdmin($merchant['id'], $data['name'], $data['email'], $data['password']);

            return $merchant;
        } catch (\Exception $e) {
            throw $e;
        }

    }

    public static function update($merchant_id, $data)
    {
        try {
            $merchant = MerchantRepository::update($merchant_id, self::filterBaseMerchantData($data));

            return $merchant;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function delete($merchant_id)
    {
        return MerchantRepository::delete($merchant_id);
    }

    public static function lists()
    {
        return MerchantRepository::lists();
    }

    public static function show($merchant_id)
    {
        return MerchantRepository::show($merchant_id);
    }


}

<?php namespace App\Services\Administrator;

use App\Models\Access\Role\Role;

class AdministratorService {

    const DEFAULT_MERCHANT_ROLE = 'MerchantAdmin';

    public static function createMerchantAdmin($merchant_id, $name, $password, $email, $role_name = self::DEFAULT_MERCHANT_ROLE)
    {
        //创建商家管理员,默认无需激活
        $data = compact('name', 'password', 'email');
        $data['status'] = 1;
        $data['confirmed'] = 1;
        $roles['assignees_roles'][] = self::getAdminRole($role_name);
        $permissions['permission_user'] = [];
        $merchant_admin_user = app('\App\Repositories\Backend\User\EloquentUserRepository')->create($data, $roles, $permissions);

        $merchant_admin = AdministratorRepository::createMerchantAdmin($merchant_admin_user['id'], $merchant_id, $data['name']);

        return $merchant_admin_user;
    }

    public static function updateMerchantAdmin($user_id, $merchant_id, $username)
    {
        return AdministratorRepository::updateMerchantAdmin($user_id, $merchant_id, $username);
    }

    public static function deleteMerchantAdmin($user_id)
    {
        return AdministratorRepository::deleteMerchantAdmin($user_id);
    }

    public static function userMerchantAdmin($user_id)
    {
        return AdministratorRepository::getByUser($user_id);
    }


    /**
     * @return mixed
     */

    protected static function getAdminRole($role_name)
    {

        $role = Role::where('name', $role_name)->first();
        if ( ! $role) {
            $role = Role::where('name', self::DEFAULT_MERCHANT_ROLE)->first();
        }

        return $role;
    }


    public static function active()
    {

    }

    public static function block()
    {

    }

    public static function createChild()
    {

    }

    public static function bindParent()
    {

    }

    public static function updatePassword()
    {

    }

    public static function sendEmail()
    {

    }

}

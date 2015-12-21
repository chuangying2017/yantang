<?php namespace App\Services\Merchant;

use App\Models\Access\Role\Role;
use App\Repositories\Backend\User\EloquentUserRepository;
use App\Services\Administrator\AdministratorService;

/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 2/12/2015
 * Time: 5:33 PM
 */
class MerchantService {

    const DEFAULT_MERCHANT_ROLE = 'MerchantAdmin';

    /**
     * @var EloquentUserRepository
     */
    private $userRepository;

    /**
     * MerchantService constructor.
     * @param EloquentUserRepository $userRepository
     */
    public function __construct(EloquentUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public static function getMerchantIdByUserId($user_id)
    {
        #todo @bryant 实现通过user id 获取 merchant id
        return 0;
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
            //创建商家最高权限管理员,默认无需激活
            $data['status'] = 1;
            $data['confirmed'] = 1;
            $roles['assignees_roles'][] = self::getDefaultMerchantRole();
            $permissions['permission_user'] = [];
            $user = $this->userRepository->create($data, $roles, $permissions);

            $merchant_admin = AdministratorService::createMerchantAdmin($user['id'], $merchant['id'], array_get($data, 'name'));

            return $merchant;
        } catch (\Exception $e) {
            throw $e;
        }

    }

    public static function update()
    {

    }

    public static function delete()
    {
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

    /**
     * @return mixed
     */
    public static function getDefaultMerchantRole()
    {
        if (is_numeric(self::DEFAULT_MERCHANT_ROLE))
            return Role::where('id', (int)self::DEFAULT_MERCHANT_ROLE)->first();

        return Role::where('name', self::DEFAULT_MERCHANT_ROLE)->first();
    }
}

<?php namespace App\Services\Auth;

use App\Repositories\Frontend\User\EloquentUserRepository;

class AccountService {


    public static function updatePhone($current_user, $phone)
    {
        $phone_user = EloquentUserRepository::findUserByPhone($phone);

        //手机已存在
        if ($phone_user) {
            //已存在用户与当前用户不同
            if ($phone_user['id'] !== $current_user['id']) {
                //当前用户不是临时用户
                if ( ! is_null($current_user['phone'])) {
                    throw new \Exception('手机号码已经被使用');
                }

                //绑定临时用户到用户
                $current_user->providers()->update(['user_id' => $phone_user['user_id']]);

                return $phone_user;
            }
        }

        //手机不存在时更新手机,激活用户
        if ($phone_user['phone'] != $current_user['phone']) {
            $phone_user = EloquentUserRepository::updatePhone($current_user['id'], $phone);
        }

        return $phone_user;
    }


}

<?php namespace App\Api\V1\Transformers\Auth;

use App\Models\Access\User\User;
use JWTAuth;
use League\Fractal\TransformerAbstract;

class UserLoginTransformer extends  TransformerAbstract {

	/**
     * @param $token
     * @return array
     */
    public function transform(User $user)
    {
        $token = JWTAuth::fromUser($user);

        $data = [
            'token' => $token,
            'provider_id' => $user->providers->where('provider','weixin')->first()->provider_id,
            'roles' => $this->getRoles($user)
        ];

        return $data;
    }


    public static function getRoles(User $user)
    {
        $roles = [];
        if (count($user->roles)) {
            foreach ($user->roles as $role) {
                array_push($roles, $role->name);
            }
        }

        return $roles;
    }

}

<?php namespace App\Api\V1\Transformers\Auth;

use JWTAuth;
use League\Fractal\TransformerAbstract;

class UserLoginTransformer extends TransformerAbstract {

	/**
     * @param $token
     * @return array
     */
    public function transform($token)
    {
        $user = JWTAuth::toUser($token);

        $data = [
            'token' => $token,
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

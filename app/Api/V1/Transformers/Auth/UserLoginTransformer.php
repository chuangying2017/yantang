<?php namespace App\Api\V1\Transformers\Auth;

use App\Models\Access\User\User;
use JWTAuth;
use League\Fractal\TransformerAbstract;

class UserLoginTransformer extends  TransformerAbstract {

    /**
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        $token = JWTAuth::fromUser($user);
        $providers = $user->providers;
        $provider_id = null;
        if(!$providers->isEmpty()){
            $wxProvider = $providers->where('provider','weixin')->first();
            if( $wxProvider ){
                $provider_id = $wxProvider->provider_id;
            }
        }

        $data = [
            'token' => $token,
            'provider_id' => $provider_id,
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

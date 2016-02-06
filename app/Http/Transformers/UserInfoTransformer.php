<?php namespace App\Http\Transformers;

use App\Models\Access\User\User;
use League\Fractal\TransformerAbstract;

class UserInfoTransformer extends TransformerAbstract {

    public function transform(User $user)
    {
        $base_info = [
            'name'   => $user['name'],
            'email'  => $user['email'],
            'phone'  => $user['phone'],
            'status' => $user['status'],
            'roles'  => $this->getRoles($user),
            'providers' => $this->getProviderInfo($user)
        ];
        $client_info = $this->getClientInfo($user);


        return array_merge($base_info, $client_info);
    }

    protected function getClientInfo(User $user)
    {
        $clint_info = [
            'nickname' => null,
            'birthday' => null,
            'avatar'   => null,
            'sex'      => null,
        ];
        if (isset($user->client)) {
            $client = $user->client;
            $clint_info['nickname'] = $client['nickname'];
            $clint_info['birthday'] = $client['birthday'];
            $clint_info['avatar'] = $client['avatar'];
            $clint_info['sex'] = $client['sex'];
        }

        return $clint_info;
    }

    protected function getProviderInfo(User $user)
    {
        $providers = $user->providers;

        $data = [];

        if ( ! is_null($providers) && count($providers)) {
            foreach ($providers as $provider) {
                $data[ $provider['provider'] ] = [
                    'provider'    => $provider['provider'],
                    'provider_id' => $provider['provider_id'],
                ];
                if ($provider['provider'] == 'weixin' || $provider['provider'] == 'weixin_web') {
                    $data[ $provider['provider'] ]['openid'] = $provider['provider_id'];
                    $data[ $provider['provider'] ]['unionid'] = $provider['union_id'];
                }
            }
        }

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

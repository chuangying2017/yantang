<?php namespace App\Api\V1\Transformers\Auth;

use App\Models\Access\User\User;
use League\Fractal\TransformerAbstract;

class UserInfoTransformer extends TransformerAbstract {

    public function transform(User $user)
    {
        $base_info = [
            'username'      => $user['username'],
            'email'     => $user['email'],
            'phone'     => $user['phone'],
            'status'    => $user['status'],
            'roles'     => $this->getRoles($user),
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
                $provider_name = $provider['provider'];
                $provider_data = [
                    'provider'    => $provider['provider'],
                    'provider_id' => $provider['provider_id'],
                ];
                if ($provider_name == 'weixin' || $provider_name == 'weixin_web') {
                    $provider_data['openid'] = $provider['provider_id'];
                    $provider_data['unionid'] = $provider['union_id'];
                }

                array_set($data, $provider_name, $provider_data);
            }
        }

        return count($data) ? $data : json_decode('{}');
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

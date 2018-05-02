<?php namespace App\Api\V1\Transformers\Subscribe\Station;

use App\Models\Access\User\User;
use League\Fractal\TransformerAbstract;

class StationUserTransformer extends TransformerAbstract {

    public function transform(User $user)
    {
        if ($user->client) {
            return [
                'id' => $user['id'],
                'phone' => $user['phone'],
                'nickname' => $user['client']['nickname'],
                'avatar' => $user['client']['avatar'],
            ];
        }

        return [
            'id' => $user['id'],
            'phone' => $user['phone'],
        ];
    }

}

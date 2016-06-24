<?php namespace App\Api\V1\Transformers\Admin\Client;

use App\Models\Client\UserGroupAbstract;
use League\Fractal\TransformerAbstract;

class UserGroupTransformer extends TransformerAbstract {


    protected $availableIncludes = ['users'];

    public function transform(UserGroupAbstract $group)
    {
        return [
            'id' => $group['id'],
            'name' => $group['name'],
            'cover_image' => $group['cover_image'],
            'priority' => $group['priority'],
            'user_count' => $group['user_count']
        ];
    }


}

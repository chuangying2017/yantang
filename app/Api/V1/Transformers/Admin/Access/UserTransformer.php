<?php namespace App\Api\V1\Transformers\Admin\Access;

use App\Api\V1\Transformers\Traits\SetInclude;
use App\Models\Access\User\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract {

    use SetInclude;

    protected $availableIncludes = ['roles'];

    public function transform(User $user)
    {
        $this->setInclude($user);
        $data = [
            'id' => $user['id'],
            'username' => $user['username'],
            'phone' => $user['phone'],
            'status' => $user['status'],
            'confirmed' => $user['confirmed'],
            'created_at' => $user['created_at']->toDatetimeString(),
        ];
        if (!is_null($user->roles)) {
            $data['role_ids'] = $user->roles->lists('id')->all();
        }

        return $data;
    }

    public function includeRoles(User $user)
    {
        return $this->collection($user->roles, new RoleTransformer(), true);
    }

}

<?php namespace App\Api\V1\Transformers\Admin\Access;

use App\Models\Access\Role\Role;
use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract {

    public function transform(Role $role)
    {
        return [
            'id' => $role['id'],
            'name' => $role['name'],
            'sort' => $role['sort'],
            'all' => $role['all']
        ];
    }

}

<?php namespace App\Api\V1\Transformers\Admin\Access;

use App\Models\Access\Permission\Permission;
use League\Fractal\TransformerAbstract;

class PermissionTransformer extends TransformerAbstract {

    public function transform(Permission $permission)
    {
        return [
            'id' => $permission['id'],
            'name' => $permission['name'],
            'group_id' => $permission['group_id'],
            'display_name' => $permission['display_name'],
            'system' => $permission['system'],
            'sort' => $permission['sort'],
            'created_at' => $permission['created_at'],
        ];
    }

}

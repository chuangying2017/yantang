<?php namespace App\Api\V1\Transformers\Admin\Access;

use App\Models\Access\Permission\PermissionGroup;
use League\Fractal\TransformerAbstract;

class PermissionGroupTransformer extends TransformerAbstract {

    public function transform(PermissionGroup $group)
    {
        return [
            'id' => $group['id'],
            'name' => $group['name'],
            'sort' => $group['sort'],
            'parent_id' => $group['parent_id']
        ];
    }

}

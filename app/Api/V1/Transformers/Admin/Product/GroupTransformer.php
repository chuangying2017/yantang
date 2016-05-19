<?php namespace App\Api\V1\Transformers\Admin\Product;


use App\Models\Product\Group;
use League\Fractal\TransformerAbstract;

class GroupTransformer extends TransformerAbstract {

    public function transform(Group $group)
    {
        return $group->toArray();
    }

}

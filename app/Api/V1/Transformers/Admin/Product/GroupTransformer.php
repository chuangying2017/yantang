<?php namespace App\Api\V1\Transformers\Admin\Product;


use App\Models\Product\Group;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\TransformerAbstract;

class GroupTransformer extends TransformerAbstract {

    public function transform($group)
    {
        return $group->toArray();
    }

}

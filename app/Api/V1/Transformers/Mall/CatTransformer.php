<?php namespace App\Api\V1\Transformers\Mall;

use App\Models\Product\CategoryAbstract;
use App\Models\Product\Group;
use League\Fractal\TransformerAbstract;

class CatTransformer extends TransformerAbstract {

    public function transform(CategoryAbstract $group)
    {
        return [
            'id' => $group['id'],
            'name' => $group['name'],
            'cover_image' => $group['cover_image'],
            'index' => $group['index'],
            'item_count' => $group['item_count'],
            'sub_count' => $group['sub_cat_count'],
            'desc' => $group['desc'],
        ];
    }

}

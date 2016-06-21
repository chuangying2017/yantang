<?php namespace App\Api\V1\Transformers\Mall;

use App\Models\Product\Brand;
use League\Fractal\TransformerAbstract;

class BrandTransformer extends TransformerAbstract {

    public function transform(Brand $brand)
    {
        return [
            'id' => $brand['id'],
            'name' => $brand['name'],
            'cover_image' => $brand['cover_image'],
            'index' => $brand['index'],
            'item_count' => $brand['item_count'],
            'sub_count' => $brand['sub_cat_count'],
            'desc' => $brand['desc'],
        ];
    }

}

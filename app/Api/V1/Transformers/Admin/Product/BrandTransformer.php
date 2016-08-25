<?php namespace App\Api\V1\Transformers\Admin\Product;


use App\Models\Product\Brand;
use League\Fractal\TransformerAbstract;

class BrandTransformer extends TransformerAbstract {

    public function transform(Brand $brand)
    {
        return $brand->toArray();
    }

}

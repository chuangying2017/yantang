<?php namespace App\Http\Transformers;

use App\Models\Brand;
use League\Fractal\TransformerAbstract;

class BrandTransformer extends TransformerAbstract {

    public function transform(Brand $brand)
    {
        return [
            'id'          => (int)$brand->id,
            'name'        => $brand->name,
            'cover_image' => $brand->cover_image,
            'product_count' => $brand->product_count,
        ];
    }
}

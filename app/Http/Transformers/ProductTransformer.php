<?php namespace App\Http\Transformers;

use App\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract {

    public function transform(Product $product)
    {
        return [
            'id' => (int) $product->id,
            'images'
        ];
    }

}

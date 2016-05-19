<?php namespace App\Api\V1\Transformers\Admin\Product;

use App\Models\Product\ProductSku;
use League\Fractal\TransformerAbstract;

class ProductSkuTransformer extends TransformerAbstract {

    public function transform(ProductSku $products)
    {
        return [
            'name' => $products['name']
        ];
    }

}

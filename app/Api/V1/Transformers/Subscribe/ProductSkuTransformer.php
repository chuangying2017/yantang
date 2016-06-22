<?php namespace App\Api\V1\Transformers\Subscribe;

use App\Models\Product\ProductSku;
use League\Fractal\TransformerAbstract;

class ProductSkuTransformer extends TransformerAbstract {

    public function transform(ProductSku $sku)
    {
        return [
            'id' => $sku['id'],
            'name' => $sku['name'],
            'cover_image' => $sku['cover_image'],
            'price' => display_price($sku['subscribe_price']),
            'attr' => json_decode($sku['attr'], true),
        ];
    }
}

<?php namespace App\Api\V1\Transformers\Subscribe;

use App\Models\Product\Product;
use League\Fractal\TransformerAbstract;

class ProductSkuTransformer extends TransformerAbstract {

    public function transform(Product $product)
    {
        $sku = $product->skus->first();
        $cat = $product->cats->first();
        $info = $product->info;
        return [
            'id' => $sku['id'],
            'name' => $sku['name'],
            'cover_image' => $sku['cover_image'],
            'cat' => [
                'id' => $cat['id'],
                'name' => $cat['name']
            ],
            'price' => display_price($sku['subscribe_price']),
            'attr' => json_decode($sku['attr'], true),
            'detail' => $info['detail']
        ];
    }
}

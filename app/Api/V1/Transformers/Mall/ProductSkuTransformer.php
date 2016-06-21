<?php namespace App\Api\V1\Transformers\Mall;

use App\Models\Product\ProductSku;
use League\Fractal\TransformerAbstract;

class ProductSkuTransformer extends TransformerAbstract {

    public function transform(ProductSku $sku)
    {
        return [
            'id' => $sku['id'],
            'sku_no' => $sku['sku_no'],
            'name' => $sku['name'],
            'cover_image' => $sku['cover_image'],
            'product' => ['id' => $sku['product_id']],
            'price' => display_price($sku['price']),
            'display_price' => display_price($sku['display_price']),
            'stock' => $sku['stock'],
            'sales' => $sku['sales'],
            'attr' => json_decode($sku['attr'], true),
        ];
    }
}

<?php namespace App\Api\V1\Transformers\Admin\Product;

use App\Models\Product\ProductSku;
use League\Fractal\TransformerAbstract;

class ProductSkuTransformer extends TransformerAbstract {

    public function transform(ProductSku $sku)
    {
        return [
            'id' => $sku['id'],
            'name' => $sku['name'],
            'bar_code' => $sku['bar_code'],
            'sku_no' => $sku['sku_no'],
            'product' => ['id' => $sku['product_id']],
            'cover_image' => $sku['cover_image'],
            'price' => display_price($sku['price']),
            'display_price' => display_price($sku['display_price']),
            'income_price' => display_price($sku['income_price']),
            'settle_price' => display_price($sku['settle_price']),
            'express_fee' => display_price($sku['express_fee']),
            'stock' => $sku['stock'],
            'unit' => $sku['unit'],
            'sales' => $sku['sales'],
            'attr' => json_decode($sku['attr'], true),
            'created_at' => $sku['created_at'],
            'updated_at' => $sku['updated_at'],
            'product_row' => $sku['product_row'],
        ];
    }

}

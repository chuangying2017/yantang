<?php namespace App\Http\Transformers;

use App\Models\ProductSkuView;
use League\Fractal\TransformerAbstract;

class ProductSkuTransformer extends TransformerAbstract {

    public function transform(ProductSkuView $productSku)
    {
        $attributes = $productSku->attributes ? json_decode($productSku->attributes, true) : null;
        $attribute_value_ids = [];
        if (count($attributes)) {
            foreach ($attributes as $attribute) {
                $attribute_value_ids[] = array_get($attribute, 'attribute_value_id', null);
            }
        }

        return [
            'id'                  => (int)$productSku->id,
            'product_id'          => (int)$productSku->product_id,
            'sku_no'              => $productSku->sku_no,
            'stock'               => (int)$productSku->stock,
            'sales'               => (int)$productSku->sales,
            'price'               => display_price($productSku->price),
            'merchant_id'         => (int)$productSku->merchant_id,
            'name'                => $productSku->title,
            'category_id'         => (int)$productSku->category_id,
            'cover_image'         => $productSku->cover_image,
            'attributes'          => $attributes,
            'attribute_value_ids' => $attribute_value_ids
        ];
    }


}

<?php namespace App\Http\Transformers;

use App\Models\ProductSkuView;
use League\Fractal\TransformerAbstract;

class ProductSkuTransformer extends TransformerAbstract {

    public function transform(ProductSkuView $productSku)
    {
        $attributes = $productSku->attributes ? json_decode('[' . $productSku->attributes . ']', true) : null;
        $attributes_values_id = [];
        if (count($attributes)) {
            foreach ($attributes as $attribute) {
                $attributes_values_id[] = array_get($attribute, 'attribute_value_id', null);
            }
        }

        return [
            'id'                   => (int)$productSku->id,
            'product_id'           => (int)$productSku->product_id,
            'sku_no'               => $productSku->sku_no,
            'stocks'               => (int)$productSku->stock,
            'sales'                => (int)$productSku->sales,
            'price'                => (int)$productSku->price,
            'merchant_id'          => (int)$productSku->merchant_id,
            'title'                => $productSku->title,
            'category_id'          => (int)$productSku->category_id,
            'cover_image'          => $productSku->cover_image,
            'attributes'           => $attributes,
            'attributes_values_id' => $attributes_values_id
        ];
    }


}

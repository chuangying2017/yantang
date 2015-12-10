<?php namespace App\Http\Transformers;

use App\Models\ProductSkuView;
use League\Fractal\TransformerAbstract;

class ProductSkuTransformer extends TransformerAbstract {

    public function transform(ProductSkuView $productSku)
    {
        return [
            'id'          => (int)$productSku->id,
            'product_id'  => (int)$productSku->product_id,
            'sku_no'      => $productSku->sku_no,
            'stocks'      => (int)$productSku->stock,
            'sales'       => (int)$productSku->sales,
            'price'       => (int)$productSku->price,
            'merchant_id' => (int)$productSku->merchant_id,
            'title'       => $productSku->title,
            'category_id' => (int)$productSku->category_id,
            'cover_image' => $productSku->cover_image,
            'attributes'  => $productSku->attributes
        ];
    }

}

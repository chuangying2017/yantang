<?php namespace App\Api\V1\Transformers\Subscribe\Preorder;


use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\PreorderSku;

class PreorderSkuTransformer extends TransformerAbstract {

    public function transform(PreorderSku $sku)
    {
        $data = [
            'product_sku_id' => $sku->product_sku_id,
            'name' => $sku->name,
            'cover_image' => $sku->cover_image,
            'quantity' => $sku['total'],
            'price' => display_price($sku->price),
            'total' => $sku['total'],
            'remain' => $sku['remain'],
            'per_day' => $sku->per_day,
            'show_unit' => $sku->sku['unit']
        ];

        return $data;
    }

}

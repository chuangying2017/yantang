<?php namespace App\Api\V1\Transformers\Subscribe\Preorder;

use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\PreorderSku;

class PreorderDeliverSkuTransformer extends TransformerAbstract {

    public function transform(PreorderSku $sku)
    {
        $data = [
            'product_sku_id' => $sku->product_sku_id,
            'name' => $sku->name,
            'cover_image' => $sku->cover_image,
            'quantity' => $sku->pivot->quantity,
        ];

        return $data;
    }

}

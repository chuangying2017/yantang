<?php namespace App\Api\V1\Transformers\Subscribe\Preorder;

use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\PreorderSku;

class PreorderSkuTransformer extends TransformerAbstract {

    public function transform(PreorderSku $sku)
    {
        $data = [
            'id' => $sku->id,
            'preorder' => ['id' => $sku->preorder_id],
            'skus' => [
                'id' => $sku->product_sku_id,
                'name' => $sku->name,
                'cover_image' => $sku->cover_image,
                'quantity' => $sku->quantity,
                'price' => display_price($sku->price),
                'total_amount' => display_price($sku->total_amount),
            ],
            'weekday' => $sku->weeday,
            'daytime' => $sku->daytime,
            'created_at' => $sku->created_at,
            'updated_at' => $sku->updated_at,
        ];

        return $data;
    }

}

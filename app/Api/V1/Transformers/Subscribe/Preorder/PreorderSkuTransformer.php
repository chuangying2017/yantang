<?php namespace App\Api\V1\Transformers\Subscribe\Preorder;

use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\PreorderSku;

class PreorderSkuTransformer extends TransformerAbstract {

    public function transform(PreorderSku $sku)
    {
        $data = [
            'id' => $sku->id,
            'preorder' => ['id' => $sku->preorder_id],
            'weekday' => $sku->weekday,
            'daytime' => $sku->daytime,
            'created_at' => $sku->created_at,
            'updated_at' => $sku->updated_at,
        ];

        return $data;
    }

}

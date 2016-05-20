<?php namespace App\Api\V1\Transformers\Subscribe\Preorder;

use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\PreorderProduct;

class PreorderProductTransformer extends TransformerAbstract
{

    public function transform(PreorderProduct $preorder_product)
    {
        $data = [
            'id' => $preorder_product->id,
            'preorder_id' => $preorder_product->preorder_id,
            'weekday' => $preorder_product->weekday,
            'sku_id' => $preorder_product->sku_id,
            'sku_name' => $preorder_product->sku_name,
            'count' => $preorder_product->count,
            'created_at' => $preorder_product->created_at,
            'updated_at' => $preorder_product->updated_at,
        ];

        return $data;
    }

}

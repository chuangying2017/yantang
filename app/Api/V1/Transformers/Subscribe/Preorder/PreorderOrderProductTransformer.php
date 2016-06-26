<?php namespace App\Api\V1\Transformers\Subscribe\Preorder;

use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\PreorderOrderProducts;

class PreorderOrderProductTransformer extends TransformerAbstract
{

    public function transform(PreorderOrderProducts $preorder_order_product)
    {
        $data = [
            'id' => $preorder_order_product->id,
            'pre_product_id' => $preorder_order_product->pre_product_id,
            'sku_id' => $preorder_order_product->sku_id,
            'price' => display_price($preorder_order_product->price),
            'sku_name' => $preorder_order_product->sku_name,
            'count' => $preorder_order_product->count,
            'created_at' => $preorder_order_product->created_at,
            'updated_at' => $preorder_order_product->updated_at,
        ];

        return $data;
    }

}

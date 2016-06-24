<?php namespace App\Api\V1\Transformers\Subscribe\Preorder;

use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\PreorderOrder;

class PreorderOrderTransformer extends TransformerAbstract
{

    public function transform(PreorderOrder $preorder_order)
    {
        $this->setDefaultIncludes(['product']);

        $data = [
            'id' => $preorder_order->id,
            'preorder_id' => $preorder_order->preorder_id,
            'record_no' => $preorder_order->record_no,
            'amount' => display_price($preorder_order->amount),
            'pay_at' => $preorder_order->pay_at,
            'deliver_at' => $preorder_order->deliver_at,
            'status' => $preorder_order->status,
            'created_at' => $preorder_order->created_at,
            'updated_at' => $preorder_order->updated_at,
        ];

        return $data;
    }

    public function includeProduct(PreorderOrder $preorder_order)
    {
        $product = $preorder_order->product;
        return $this->collection($product, new PreorderOrderProductTransformer());
    }

}

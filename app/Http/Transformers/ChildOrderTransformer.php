<?php namespace App\Http\Transformers;

use App\Models\ChildOrder;
use App\Models\Order;
use League\Fractal\TransformerAbstract;

class ChildOrderTransformer extends TransformerAbstract {

    public function transform(ChildOrder $order)
    {
        $includes = ['product_skus'];
        $detail = [];
        $this->setDefaultIncludes($includes);

        $base_info = [
            'order_no'     => $order->order_no,
            'total_amount' => display_price($order->total_amount),
            'discount_fee' => display_price($order->discount_amount),
            'pay_amount'   => display_price($order->pay_amount),
            'status'       => $order->status,
        ];

        return array_merge($base_info, $detail);
    }

    public function includeProductSkus(ChildOrder $order)
    {
        $skus = $order->skus;

        return $this->collection($skus, new OrderProductSkusTransformer());
    }
}

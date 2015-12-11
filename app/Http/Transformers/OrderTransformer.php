<?php namespace App\Http\Transformers;

use App\Models\Order;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract {

    public function transform(Order $order)
    {
        $includes = ['product_skus'];
        if (isset($order['show_full'])) {
            $includes = ['product_skus', 'address'];
        }
        $this->setDefaultIncludes($includes);

        return [
            'order_no'     => $order->order_no,
            'total_amount' => (int)$order->total_amount,
            'discount_fee' => (int)$order->discount_amount,
            'user_id'      => $order->user_id,
            'memo'         => $order->memo,
            'created_at'   => $order->created_at->toDateTimeString(),
            'pay_at'       => $order->pay_at,
            'status'       => $order->status
        ];
    }

    public function includeProductSkus(Order $order)
    {
        $skus = $order->skus;

        return $this->collection($skus, new OrderProductSkusTransformer());
    }
}

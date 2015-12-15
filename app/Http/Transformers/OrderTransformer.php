<?php namespace App\Http\Transformers;

use App\Models\Order;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract {

    public function transform(Order $order)
    {
        $includes = ['product_skus'];
        $detail = [];
        if (isset($order['show_full'])) {
            $includes = ['product_skus', 'address'];
            $detail = [
                'payment_method' => $order->pay_type,
                'express'        => isset($order->express)
                    ? [
                        'company_id'   => $order->express->company_id,
                        'company_name' => $order->express->company_name,
                        'post_no'      => $order->express->post_no,
                        'deliver_at'   => $order->express->deliver_at,
                    ]
                    : null,
            ];
        }
        $this->setDefaultIncludes($includes);

        $base_info = [
            'order_no'     => $order->order_no,
            'total_amount' => (int)$order->total_amount,
            'discount_fee' => (int)$order->discount_amount,
            'user_id'      => $order->user_id,
            'memo'         => $order->memo,
            'created_at'   => $order->created_at->toDateTimeString(),
            'pay_at'       => $order->pay_at,
            'status'       => $order->status,
        ];

        return array_merge($base_info, $detail);
    }

    public function includeAddress(Order $order)
    {
        $address = $order->address;

        return $this->item($address, new AddressTransformer());
    }

    public function includeProductSkus(Order $order)
    {
        $skus = $order->skus;

        return $this->collection($skus, new OrderProductSkusTransformer());
    }
}

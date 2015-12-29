<?php namespace App\Http\Transformers;

use App\Models\Order;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract {

    public function transform(Order $order)
    {
        $includes = ['child_orders'];
        $detail = [];
        if (isset($order['show_full'])) {
            $includes = ['child_orders', 'address'];
            $detail = [
                'express' => isset($order->express)
                    ? [
                        'company_id'   => (int)$order->express->company_id,
                        'company_name' => $order->express->company_name,
                        'post_no'      => $order->express->post_no,
                        'deliver_at'   => $order->express->deliver_at,
                    ]
                    : null,
            ];
        }
        $this->setDefaultIncludes($includes);

        $base_info = [
            'order_no'       => $order->order_no,
            'total_amount'   => display_price($order->total_amount),
            'discount_fee'   => display_price($order->discount_amount),
            'pay_amount'     => display_price($order->pay_amount),
            'user_id'        => (int)$order->user_id,
            'memo'           => $order->memo,
            'created_at'     => $order->created_at->toDateTimeString(),
            'pay_at'         => $order->pay_at,
            'status'         => $order->status,
            'payment_method' => $order->pay_type,
        ];

        return array_merge($base_info, $detail);
    }

    public function includeAddress(Order $order)
    {
        $address = $order->address;

        return $this->item($address, new AddressTransformer());
    }

    public function includeChildOrders(Order $order)
    {
        $child_orders = $order->children;

        return $this->collection($child_orders, new ChildOrderTransformer());
    }
}

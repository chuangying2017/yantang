<?php namespace App\Http\Transformers;

use App\Models\Order;
use App\Models\OrderRefund;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract {

    protected $show_full;

    public function transform(Order $order)
    {
        $this->show_full = isset($order['show_full']) ? 1 : 0;

        $includes = ['child_orders', 'address', 'refund_order'];
        if ($this->show_full) {
            $includes = ['child_orders', 'address', 'refund_order'];
        }
        $this->setDefaultIncludes($includes);

        $base_info = [
            'order_no'      => $order->order_no,
            'total_amount'  => display_price($order->total_amount),
            'discount_fee'  => display_price($order->discount_amount),
            'pay_amount'    => display_price($order->pay_amount),
            'user_id'       => (int)$order->user_id,
            'memo'          => $order->memo,
            'created_at'    => $order->created_at->toDateTimeString(),
            'pay_at'        => $order->pay_at,
            'status'        => $order->status,
            'pay_type'      => $order->pay_type,
            'refund_status' => $order->refund_status,
            'refund_amount' => $order->refund_amount,
            'refund_at'     => $order->refund_at,
        ];

        return array_merge($base_info);
    }

    public function includeAddress(Order $order)
    {
        $address = $order->address;

        return $this->item($address, new AddressTransformer());
    }

    public function includeChildOrders(Order $order)
    {
        $child_orders = $order->children;

        return $this->collection($child_orders, new ChildOrderTransformer($this->show_full));
    }

    public function includeRefundOrder(Order $order)
    {
        $refund = $order->refund;
        return $this->collection($refund, new OrderRefundTransformer());
    }


}

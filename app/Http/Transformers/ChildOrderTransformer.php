<?php namespace App\Http\Transformers;

use App\Models\ChildOrder;
use App\Models\Order\Order;
use League\Fractal\TransformerAbstract;

class ChildOrderTransformer extends TransformerAbstract {

    public function __construct($show_full = 0)
    {
        $this->show_full = $show_full;
    }

    public function transform(ChildOrder $order)
    {
        $includes = ['product_skus'];

        if ($this->show_full) {
            array_push($includes, 'deliver');
        }

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

    public function includeDeliver(ChildOrder $order)
    {
        $deliver = $order->deliver;

        if(!is_null($deliver)) {
            return $this->item($deliver, new ExpressTransformer());
        }

        return $deliver;
    }
}

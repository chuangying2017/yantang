<?php namespace App\Api\V1\Transformers\Mall;

use App\Api\V1\Transformers\Subscribe\Preorder\PreorderTransformer;
use App\Api\V1\Transformers\Traits\SetInclude;
use App\Models\Order\Order;
use League\Fractal\TransformerAbstract;

class ClientOrderTransformer extends TransformerAbstract {

    use SetInclude;

    protected $availableIncludes = ['skus', 'address', 'billings', 'deliver', 'preorder'];

    public function transform(Order $order)
    {
        $this->setInclude($order);

        return [
            'id' => $order['id'],
            'user_id' => $order['user_id'],
            'order_no' => $order['order_no'],
            'total_amount' => display_price($order['total_amount']),
            'discount_amount' => display_price($order['discount_amount']),
            'pay_amount' => display_price($order['pay_amount']),
            'deliver_type' => $order['deliver_type'],
            'pay_type' => $order['pay_type'],
            'pay_channel' => $order['pay_channel'],
            'status' => $order['status'],
            'pay_status' => $order['pay_status'],
            'refund_status' => $order['refund_status'],
            'pay_at' => $order['pay_at'],
            'deliver_at' => $order['deliver_at'],
            'cancel_at' => $order['cancel_at'],
            'done_at' => $order['done_at'],
            'created_at' => $order['created_at'],
        ];
    }

    public function includeSkus(Order $order)
    {
        return $this->collection($order['skus'], new OrderSkuTransformer(), true);
    }

    public function includeAddress(Order $order)
    {
        return $this->item($order['address'], new OrderAddressTransformer(), true);
    }

    public function includeBillings(Order $order)
    {
        return $this->collection($order->billings, new ClientOrderBillingTransformer(), true);
    }

    public function includeDeliver(Order $order)
    {
        return $this->item($order['deliver'], new OrderDeliverTransformer(), true);
    }

    public function includePreorder(Order $order)
    {
        return $this->item($order->preorder, new PreorderTransformer(), true);
    }

}

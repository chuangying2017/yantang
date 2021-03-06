<?php namespace App\Api\V1\Transformers;

use App\Models\Collect\CollectOrder;
use League\Fractal\TransformerAbstract;

class CollectOrderTransformer extends TransformerAbstract {
    public function transform(CollectOrder $collect_order)
    {
        $data = $collect_order->toArray();
        $data['residence'] = !$collect_order['residence'] ? '(无)' : $collect_order['residence']->name;

        $data['order'] = $collect_order->order;
        $data['address'] = $collect_order->address;
        $data['sku'] = $collect_order->sku;
        return $data;
    }
}

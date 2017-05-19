<?php namespace App\Api\V1\Transformers\Admin\Order;

use App\Models\Collect\CollectOrder;
use League\Fractal\TransformerAbstract;

class AdminCollectOrderTransformer extends TransformerAbstract
{
    public function transform(CollectOrder $collect_order)
    {
        $data = $collect_order->toArray();
        $data['order'] = $collect_order->order;
        $data['address'] = $collect_order->address;
        $data['sku'] = $collect_order->sku;
        if ($data['order']) {
            $data['order']['pay_amount'] = display_price($data['order']['pay_amount']);
            $data['order']['total_amount'] = display_price($data['order']['total_amount']);
            $data['order']['products_amount'] = display_price($data['order']['products_amount']);
            $data['order']['discount_amount'] = display_price($data['order']['discount_amount']);
        }
        if ($data['sku']) {
            $data['sku']['price'] = display_price($data['sku']['price']);
            $data['sku']['display_price'] = display_price($data['sku']['display_price']);
            $data['sku']['express_fee'] = display_price($data['sku']['express_fee']);
            $data['sku']['income_price'] = display_price($data['sku']['income_price']);
            $data['sku']['settle_price'] = display_price($data['sku']['settle_price']);
            $data['sku']['subscribe_price'] = display_price($data['sku']['subscribe_price']);
            $data['sku']['service_fee'] = display_price($data['sku']['service_fee']);
        }
        return $data;
    }
}

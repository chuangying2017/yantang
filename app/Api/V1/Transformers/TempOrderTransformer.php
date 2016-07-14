<?php namespace App\Api\V1\Transformers;

use App\Services\Order\Generator\TempOrder;
use League\Fractal\TransformerAbstract;

class TempOrderTransformer extends TransformerAbstract {

    public function transform(TempOrder $temp_order)
    {
        $temp_order = $temp_order->toArray();
        $temp_order['total_amount'] = display_price($temp_order['total_amount']);
        $temp_order['products_amount'] = display_price($temp_order['products_amount']);
        $temp_order['pay_amount'] = display_price($temp_order['pay_amount']);
        $temp_order['express_fee'] = display_price($temp_order['express_fee']);
        $temp_order['skus'] = $this->transSkus($temp_order);
        $temp_order['preorder'] = $this->transPreorder($temp_order);
        return $temp_order;
    }

    protected function transSkus($temp_order)
    {
        foreach ($temp_order['skus'] as $key => $sku) {
            $temp_order['skus'][$key]['price'] = display_price($sku['price']);
            $temp_order['skus'][$key]['settle_price'] = display_price($sku['settle_price']);
            $temp_order['skus'][$key]['display_price'] = display_price($sku['display_price']);
            $temp_order['skus'][$key]['subscribe_price'] = display_price($sku['subscribe_price']);
            $temp_order['skus'][$key]['total_amount'] = display_price($sku['total_amount']);
            $temp_order['skus'][$key]['pay_amount'] = display_price($sku['pay_amount']);
        }

        return $temp_order['skus'];
    }

    protected function transPreorder($temp_order)
    {
        if (!isset($temp_order['preorder']) || is_null($temp_order['preorder'])) {
            return null;
        }
        foreach ($temp_order['preorder']['skus'] as $key => $sku) {
            $temp_order['preorder']['skus'][$key]['price'] = display_price($sku['price']);
        }

        return $temp_order['preorder'];
    }

}

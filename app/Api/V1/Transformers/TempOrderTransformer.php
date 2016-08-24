<?php namespace App\Api\V1\Transformers;

use App\Services\Order\Generator\TempOrder;
use League\Fractal\TransformerAbstract;

class TempOrderTransformer extends TransformerAbstract {

    public function transform(TempOrder $temp_order)
    {
        $data = $temp_order->toArray();
        unset($data['user']);
        unset($data['rules']);
        $data['total_amount'] = display_price($data['total_amount']);
        $data['products_amount'] = display_price($data['products_amount']);
        $data['discount_amount'] = display_price($data['discount_amount']);
        $data['pay_amount'] = display_price($data['pay_amount']);
        $data['express_fee'] = display_price($data['express_fee']);
        $data['skus'] = $this->transSkus($data);
        $data['preorder'] = $this->transPreorder($data);
        $data['coupons'] = $this->transCoupons($data);
        $data['campaigns'] = $this->transCampaigns($data);
        return $data;
    }

    protected function transSkus($temp_order)
    {
        $skus = $temp_order['skus']->toArray();
        foreach ($skus as $key => $sku) {
            $skus[$key]['price'] = display_price($sku['price']);
            $skus[$key]['settle_price'] = display_price($sku['settle_price']);
            $skus[$key]['display_price'] = display_price($sku['display_price']);
            $skus[$key]['subscribe_price'] = display_price($sku['subscribe_price']);
            $skus[$key]['total_amount'] = display_price($sku['total_amount']);
            $skus[$key]['discount_amount'] = display_price($sku['discount_amount']);
            $skus[$key]['pay_amount'] = display_price($sku['pay_amount']);
            $skus[$key]['income_price'] = display_price($sku['income_price']);
        }

        return $skus;
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

    protected function transCoupons($temp_order)
    {
        $coupons = [];
        if (isset($temp_order['coupons']) && !empty($temp_order['coupons'])) {
            foreach ($temp_order['coupons'] as $key => $coupon) {
                $coupons[$key] = array_only($coupon, [
                    'id',
                    'name',
                    'content',
                    'desc',
                    'promotion',
                    'multi',
                    'related',
                    'ticket',
                    'message'
                ]);
                $coupons[$key]['using'] = array_get($coupon, 'using', 0);
                $coupons[$key]['usable'] = array_get($coupon, 'usable', 0);
            }
        }
        return $coupons;
    }

    public function transCampaigns($temp_order)
    {
        $campaigns = [];
        if (isset($temp_order['campaigns']) && !empty($temp_order['campaigns'])) {
            foreach ($temp_order['campaigns'] as $key => $campaign) {
                $campaigns[$key] = array_only($campaign, [
                    'id',
                    'name',
                    'desc',
                    'promotion',
                    'multi',
                    'related',
                    'message',
                ]);
                $campaigns[$key]['using'] = array_get($campaign, 'using', 0);
                $campaigns[$key]['usable'] = array_get($campaign, 'usable', 0);
            }
        }
        return $campaigns;
    }

}

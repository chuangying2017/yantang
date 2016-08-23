<?php namespace App\Services\Promotion\Rule\Benefit;

use App\Services\Promotion\Support\PromotionAbleItemContract;

class ProductBenefit extends Benefit {

    public function cal($mode, $quantity, PromotionAbleItemContract $items, $item_option = null)
    {
        $skus = [];
        foreach ($item_option as $key => $item) {
            $skus[$key]['id'] = $item['id'];
            $skus[$key]['quantity'] = $quantity;
            $skus[$key]['total_amount'] = $quantity * $item[$items->getSkuPriceTag()];
            $skus[$key]['discount_amount'] = $skus[$key]['total_amount'];
            $skus[$key]['pay_amount'] = 0;
        }
        $benefit_values = $skus;

        return $benefit_values;
    }


}

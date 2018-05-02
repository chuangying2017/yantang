<?php namespace App\Services\Promotion\Rule\Benefit;

use App\Services\Promotion\Support\PromotionAbleItemContract;

class SpecialPriceBenefit extends Benefit {

    public function cal($mode, $value, PromotionAbleItemContract $items, $item_option = null)
    {
        $benefit_value = self::calModeValue($mode, array_get($items->getItems($item_option), $items->getSkuPriceTag()), $value);

        return $benefit_value;
    }
}

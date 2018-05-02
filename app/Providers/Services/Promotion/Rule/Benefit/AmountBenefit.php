<?php namespace App\Services\Promotion\Rule\Benefit;

use App\Services\Promotion\Support\PromotionAbleItemContract;

class AmountBenefit extends Benefit {

    public function cal($mode, $value, PromotionAbleItemContract $items, $item_keys = null)
    {
        $benefit_value = self::calModeValue($mode, $items->getAmount(array_keys($item_keys)), $value);

        return $benefit_value;
    }

}

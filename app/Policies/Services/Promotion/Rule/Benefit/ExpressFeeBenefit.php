<?php namespace App\Services\Promotion\Rule\Benefit;

use App\Services\Promotion\Support\PromotionAbleItemContract;

class ExpressFeeBenefit extends Benefit {

    public function cal($mode, $value, PromotionAbleItemContract $items, $item_option = null)
    {
        $benefit_value = self::calModeValue($mode, $items->getExpressFee(), $value);

        return $benefit_value;
    }
}

<?php namespace App\Services\Promotion\Rule\Benefit;

use App\Services\Promotion\PromotionProtocol;
use App\Services\Promotion\Support\PromotionAbleItemContract;

class AmountBenefit implements Benefit {

    public function calAndSet($mode, $value, PromotionAbleItemContract $items, $item_keys = null)
    {
        $benefit_value = PromotionProtocol::calModeValue($mode, $items->getAmount($item_keys), $value);
        if (is_null($item_keys)) {
            $items->setDiscountAmount($benefit_value);
        } else {
            $items->setSkusDiscountAmount($item_keys, $benefit_value);
        }
        return $benefit_value;
    }

    public function rollback($mode, $benefit_value, PromotionAbleItemContract $items, $item_keys = null)
    {
        if (is_null($item_keys)) {
            $items->unsetDiscountAmount($benefit_value);
        } else {
            $items->unsetSkusDiscountAmount($item_keys, $benefit_value);
        }
    }



}

<?php namespace App\Services\Promotion\Rule\Benefit;

use App\Services\Promotion\PromotionProtocol;
use App\Services\Promotion\Rule\Benefit\Setter\PromotionAbleItemBenefitContract;
use App\Services\Promotion\Rule\Benefit\Setter\PromotionAmount;
use App\Services\Promotion\Support\PromotionAbleItemContract;

class AmountBenefit extends Benefit {

    public function __construct(PromotionAmount $setter)
    {
        parent::__construct($setter);
    }

    public function calAndSet($mode, $value, PromotionAbleItemContract $items, $item_keys = null)
    {
        $benefit_value = self::calModeValue($mode, $items->getAmount($item_keys), $value);

        $this->benefit_setter->add($benefit_value, $item_keys);

        return $benefit_value;
    }

    public function rollback($mode, $benefit_value, PromotionAbleItemContract $items, $item_keys = null)
    {
        $this->benefit_setter->remove($benefit_value, $item_keys);
    }

}

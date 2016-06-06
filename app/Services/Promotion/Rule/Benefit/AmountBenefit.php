<?php namespace App\Services\Promotion\Rule\Benefit;

use App\Services\Promotion\PromotionProtocol;

class AmountBenefit implements Benefit {

    public function calculate($amount, $mode, $value)
    {
        return PromotionProtocol::calModeValue($mode, $amount, $value);
    }

}

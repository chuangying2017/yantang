<?php namespace App\Services\Promotion\Rule\Benefit;

use App\Services\Promotion\PromotionProtocol;
use App\Services\Promotion\Support\PromotionAbleItemContract;

class BenefitCalculator {
    
    /**
     * @var Benefit
     */
    protected $calculator;

    public function setBenefitType($type)
    {
        $calculator = [
            PromotionProtocol::DISCOUNT_TYPE_OF_AMOUNT => AmountBenefit::class,
            PromotionProtocol::DISCOUNT_TYPE_OF_EXPRESS => '',
            PromotionProtocol::DISCOUNT_TYPE_OF_CREDITS => '',
            PromotionProtocol::DISCOUNT_TYPE_OF_SPECIAL_PRICE => '',
            PromotionProtocol::DISCOUNT_TYPE_OF_COUPON => '',
            PromotionProtocol::DISCOUNT_TYPE_OF_PRODUCT => '',
            PromotionProtocol::DISCOUNT_TYPE_OF_GIFT => '',
        ];

        $handler = array_get($calculator, $type, null);
        if (is_null($handler)) {
            throw new \Exception('优惠计算错误');
        }

        $this->setCalculator(app()->make($handler));

        return $this;
    }

    protected function setCalculator(Benefit $calculator)
    {
        $this->calculator = $calculator;
    }

    public function cal($mode, $value, PromotionAbleItemContract $items, $item_option = null)
    {
        return $this->calculator->cal($mode, $value, $items, $item_option);
    }

}

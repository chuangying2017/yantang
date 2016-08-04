<?php namespace App\Services\Promotion\Support\Benefit;
class PromotionExpressFee implements PromotionAbleItemBenefitContract {

    protected $discount_express_fee;

    public function add($discount_amount, $key = null)
    {
        $this->discount_express_fee += $discount_amount;
    }

    public function remove($discount_amount, $key = null)
    {
        if ($this->discount_express_fee <= $discount_amount) {
            $this->discount_express_fee = 0;
        } else {
            $this->discount_express_fee -= $discount_amount;
        }
    }

    public function init($benefit_name)
    {
        $this->discount_express_fee = $benefit_name;
    }

    public function get($key = null)
    {
        return $this->discount_express_fee;
    }
}

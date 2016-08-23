<?php namespace App\Services\Promotion\Rule\Benefit\Setter;


class PromotionExpressFee implements PromotionAbleItemBenefitContract {

    protected $discount_express_fee = 0;

    protected $related_skus;

    public function init($benefit_name, $related_skus = null)
    {
        $this->related_skus = $related_skus;
        $this->discount_express_fee = $benefit_name;
    }

    public function add($discount_amount)
    {
        $this->discount_express_fee += $discount_amount;
        return $this;
    }

    public function remove($discount_amount)
    {
        if ($this->discount_express_fee <= $discount_amount) {
            $this->discount_express_fee = 0;
        } else {
            $this->discount_express_fee -= $discount_amount;
        }
    }


    public function get()
    {
        return $this->discount_express_fee;
    }

}

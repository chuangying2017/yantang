<?php namespace App\Services\Promotion\Rule\Benefit\Setter;
class PromotionAmount implements PromotionAbleItemBenefitContract {

    protected $discount_amount;

    public function init($benefit_name)
    {
        $this->discount_amount = $benefit_name;
    }

    public function add($benefit, $key = null)
    {
        $this->discount_amount += $benefit;
    }

    public function remove($benefit, $key = null)
    {
        $this->discount_amount -= $benefit;
    }

    public function get($key = null)
    {
        return $this->discount_amount;
    }
}

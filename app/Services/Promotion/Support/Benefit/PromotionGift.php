<?php namespace App\Services\Promotion\Support\Benefit;

class PromotionGift implements PromotionAbleItemBenefitContract {

    protected $gifts;

    public function init($benefit_name)
    {
        $this->gifts = $benefit_name;
    }

    public function add($benefit, $key = null)
    {
        $this->gifts[] = $benefit;
    }

    public function remove($benefit, $key = null)
    {
        // TODO: Implement remove() method.
    }

    public function get($key = null)
    {
        return $this->gifts;
    }
}

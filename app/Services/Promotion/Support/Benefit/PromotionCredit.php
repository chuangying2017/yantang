<?php namespace App\Services\Promotion\Support\Benefit;
class PromotionCredit implements PromotionAbleItemBenefitContract {

    protected $credits;

    public function init($benefit_name)
    {
        $this->credits = $benefit_name;
    }

    public function add($amount, $key = null)
    {
        $this->credits += $amount;
    }

    public function remove($amount, $key = null)
    {
        if ($this->credits <= $amount) {
            $this->credits = 0;
        } else {
            $this->credits -= $amount;
        }
    }

    public function get($key = null)
    {
        return $this->credits;
    }
}

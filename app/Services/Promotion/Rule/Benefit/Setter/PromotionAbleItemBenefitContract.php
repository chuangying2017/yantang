<?php namespace App\Services\Promotion\Rule\Benefit\Setter;
interface PromotionAbleItemBenefitContract {

    public function init($benefit_name);

    public function add($benefit, $key = null);

    public function remove($benefit, $key = null);

    public function get($key = null);

}

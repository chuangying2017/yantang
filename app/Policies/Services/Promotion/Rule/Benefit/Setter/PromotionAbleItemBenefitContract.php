<?php namespace App\Services\Promotion\Rule\Benefit\Setter;
interface PromotionAbleItemBenefitContract {

    public function init($benefit_name, $related_skus = null);

    public function add($benefit);

    public function remove($benefit);

    public function get();

}

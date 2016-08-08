<?php namespace App\Services\Promotion\Support;
use App\Services\Promotion\Rule\Data\SpecifyRuleDataContract;

interface PromotionAbleItemBenefitContract {

    public function addPromotion(SpecifyRuleDataContract $rule);

    public function removePromotion(SpecifyRuleDataContract $rule);

    public function getPromotions(SpecifyRuleDataContract $rule = null);
    

}

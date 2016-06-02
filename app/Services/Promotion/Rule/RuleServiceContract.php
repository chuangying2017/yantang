<?php namespace App\Services\Promotion\Rule;

interface RuleServiceContract {

    public function canGetPromotion($user);

    public function itemsPromotionUsage($items);

    public function calculatePromotionBenefits($items, $amount);

    public function setRule($rule);

}

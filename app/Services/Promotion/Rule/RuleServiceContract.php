<?php namespace App\Services\Promotion\Rule;

interface RuleServiceContract {

    public function canGetPromotion($user, $rule_qualify);

    public function itemsFitPromotion($items, $rule_items);

    public function itemsInRange($usable_items, $range);

    public function calculatePromotionBenefits($items, $benefits);

    public function setRule($rule);

}

<?php namespace App\Repositories\Promotion\Rule;

interface RuleRepositoryContract extends RuleQualifyRepoContract, RuleItemRepoContract {

    public function createRule($qualifies, $items, $range, $discount, $weight, $multi, $memo);

    public function updateRule($rule_id, $qualifies, $items, $range, $discount, $weight, $multi, $memo);

    public function deleteRule($rule_id);

    public function deleteRuleOfPromotion($promotion_id);

    public function getRules();

    public function getRule($rule_id);

}

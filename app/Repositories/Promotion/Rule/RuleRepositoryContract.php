<?php namespace App\Repositories\Promotion\Rule;

interface RuleRepositoryContract extends RuleQualifyRepoContract, RuleItemRepoContract {

    public function createRule($name, $desc, $qualifies, $items, $range, $discount, $weight, $multi);

    public function updateRule($rule_id, $name, $desc, $qualifies, $items, $range, $discount, $weight, $multi);

    public function deleteRule($rule_id);

    public function deleteRuleOfPromotion($promotion_id);

    public function getRules();

    public function getRule($rule_id);

}

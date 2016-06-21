<?php namespace App\Services\Promotion\Rule\Data;

interface RuleDataContract extends RuleDataSettingContract {

    public function init($rules);

    public function sortRules();

    public function getAll();

    /**
     * @param $rule_key
     * @return $this
     */
    public function setRuleKey($rule_key);

    public function unsetRule($rule_key);

    public function getRuleID();

    public function getPromotionID();

    public function getType();

    public function getQualification();

    public function getItems();

    public function getRange();

    public function getDiscount();

    public function getWeight();

    public function getMultiType();

    public function getGroup();

    public function getByGroup($group);

}

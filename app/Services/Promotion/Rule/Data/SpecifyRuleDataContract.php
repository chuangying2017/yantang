<?php namespace App\Services\Promotion\Rule\Data;
interface SpecifyRuleDataContract extends SpecifyRuleDataSettingContract{

    public function setRule($rule_key);

    public function getRule();

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
    
}

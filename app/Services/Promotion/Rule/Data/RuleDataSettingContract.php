<?php namespace App\Services\Promotion\Rule\Data;
interface RuleDataSettingContract {

    public function setRuleKey($rule_key);

    public function setRelated($item_keys);
    public function getRelatedItems();

    public function setUsable();
    public function unsetUsable();
    public function isUsable();

    public function unsetAllNotUsingUsable();
    public function unsetSameGroupUsable();

    public function setConflictUsable();

    public function setUsing();
    public function isUsing();
    public function unsetUsing();

    public function setBenefit($value);

    public function unsetBenefit();

    public function setMessage($message);

}

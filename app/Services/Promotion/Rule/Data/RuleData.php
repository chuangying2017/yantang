<?php namespace App\Services\Promotion\Rule\Data;

class RuleData implements RuleDataContract {

    protected $rules;
    protected $rule_key;

    public function init($rules)
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * 规则排序:
     * 1. 排他优先
     * 2. 分组和组内权重降序排序（分组优先,权重大优先）
     * 3. 规则对象范围小优先
     */
    public function sortRules()
    {
        $this->rules = array_multi_sort($this->rules, [
            'multi' => 'asc',
            'group' => 'desc',
            'weight' => 'desc',
            'qualify.type' => 'desc'
        ]);
    }

    public function setRuleKey($rule_key)
    {
        $this->rule_key = $rule_key;
        return $this;
    }

    public function setRelated($item_keys)
    {
        // TODO: Implement setRelated() method.
    }

    public function setUsable()
    {
        // TODO: Implement setUsable() method.
    }

    public function unsetUsable()
    {
        // TODO: Implement unsetUsable() method.
    }

    public function setUsing()
    {
        // TODO: Implement setUse() method.
    }

    public function unsetUsing()
    {
        // TODO: Implement unsetUse() method.
    }

    public function setBenefit($value)
    {
        // TODO: Implement setBenefit() method.
    }

    public function unsetBenefit()
    {
        // TODO: Implement unsetBenefit() method.
    }

    public function getType()
    {
        // TODO: Implement getType() method.
    }

    public function getItems()
    {
        // TODO: Implement getItems() method.
    }

    public function getRange()
    {
        // TODO: Implement getRange() method.
    }

    public function getDiscount()
    {
        // TODO: Implement getDiscount() method.
    }

    public function getWeight()
    {
        // TODO: Implement getWeight() method.
    }

    public function getMultiType()
    {
        // TODO: Implement getMultiType() method.
    }

    public function getAll()
    {
        return $this->rules;
    }

    public function unsetRule($rule_key)
    {
        unset($this->rules[$rule_key]);
        return $this;
    }

    public function getRuleID()
    {
        // TODO: Implement getRuleID() method.
    }

    public function getPromotionID()
    {
        // TODO: Implement getPromotionID() method.
    }

    public function getQualification()
    {
        // TODO: Implement getQualification() method.
    }

    public function getRelatedItems()
    {
        // TODO: Implement getRelatedItems() method.
    }

    public function getGroup()
    {
        // TODO: Implement getGroup() method.
    }

    public function getByGroup($group)
    {
        // TODO: Implement getByGroup() method.
    }

    public function isUsable()
    {
        // TODO: Implement isUsable() method.
    }

    public function unsetAllNotUsingUsable()
    {
        // TODO: Implement unsetAllNotUsingUsable() method.
    }

    public function unsetSameGroupUsable()
    {
        // TODO: Implement unsetSameGroupUsable() method.
    }

    public function isUsing()
    {
        // TODO: Implement isUsing() method.
    }

    public function setMessage($message)
    {
        // TODO: Implement setMessage() method.
    }
}

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

    public function getAll()
    {
        return $this->rules;
    }

    public function getAllKeys()
    {
        return array_keys($this->rules);
    }

    public function getByGroup($group)
    {
        // TODO: Implement getByGroup() method.
    }

    public function setRule($rule_key)
    {
        // TODO: Implement setRule() method.
    }

    public function getRuleID()
    {
        // TODO: Implement getRuleID() method.
    }

    public function getPromotionID()
    {
        // TODO: Implement getPromotionID() method.
    }

    public function getType()
    {
        // TODO: Implement getType() method.
    }

    public function getQualification()
    {
        // TODO: Implement getQualification() method.
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

    public function getGroup()
    {
        // TODO: Implement getGroup() method.
    }

    public function setRelated($item_keys)
    {
        // TODO: Implement setRelated() method.
    }

    public function getRelatedItems()
    {
        // TODO: Implement getRelatedItems() method.
    }

    public function setUsable()
    {
        // TODO: Implement setUsable() method.
    }

    public function unsetUsable()
    {
        // TODO: Implement unsetUsable() method.
    }

    public function isUsable()
    {
        // TODO: Implement isUsable() method.
    }

    public function unsetSameGroupUsable()
    {
        // TODO: Implement unsetSameGroupUsable() method.
    }

    public function setConflictUsable()
    {
        // TODO: Implement setConflictUsable() method.
    }

    public function setUsing()
    {
        // TODO: Implement setUsing() method.
    }

    public function isUsing()
    {
        // TODO: Implement isUsing() method.
    }

    public function unsetUsing()
    {
        // TODO: Implement unsetUsing() method.
    }

    public function setBenefit($value)
    {
        // TODO: Implement setBenefit() method.
    }

    public function unsetBenefit()
    {
        // TODO: Implement unsetBenefit() method.
    }

    public function setMessage($message)
    {
        // TODO: Implement setMessage() method.
    }


    public function getRule()
    {
        // TODO: Implement getRule() method.
    }

    public function resetRule()
    {
        // TODO: Implement resetRule() method.
    }

    public function getBenefit()
    {
        // TODO: Implement getBenefit() method.
    }

    public function unsetOtherUsable()
    {
        // TODO: Implement unsetOtherUsable() method.
    }

    public function getMessage()
    {
        // TODO: Implement getMessage() method.
    }

    public function unsetRule()
    {
        // TODO: Implement unsetRule() method.
    }
}

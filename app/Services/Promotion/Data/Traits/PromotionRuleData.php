<?php
/**
 * Created by PhpStorm.
 * User: troy
 * Date: 6/1/16
 * Time: 9:11 PM
 */

namespace App\Services\Promotion\Data\Traits;

trait PromotionRuleData {

    protected $rules;

    public function initRuleData()
    {
        foreach ($this->rules as $key => $rule) {
            $this->rules[$key]['usage'] = [
                'item_keys' => [],
                'in_range' => null,
                'selected' => false,
                'remain' => 0,
                'benefit' => 0,
            ];
        }
    }


    public function setRuleDiscount($rule_key, $discount)
    {
        $this->rules[$rule_key]['calculates']['discount'] = $discount;
        $this->rules[$rule_key]['calculates']['success'] = true;
    }

    /**
     * @param mixed $rules
     */
    public function setRules($rules, $init = false)
    {
        $this->rules = $rules;
        if ($init) {
            $this->initRuleData();
        }
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
            'weight' => 'desc',
            'qualify.type' => 'desc'
        ]);
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function getInRangeRules()
    {
        $rules = [];
        foreach ($this->rules as $rule_key => $rule) {
            if ($rule['usage']['in_range'])
                $rules[$rule_key] = $rule;
        }
        return $rules;
    }

    public function getRule($rule_key)
    {
        return $this->rules[$rule_key];
    }

    public function removeRule($rule_key)
    {
        unset($this->rules[$rule_key]);
    }

    public function addRule($rule)
    {
        array_push($this->rules, $rule);
    }

    public function getRuleItems($rule_key)
    {
        return $this->rules[$rule_key]['items'];
    }

    public function getRuleRange($rule_key)
    {
        return $this->rules[$rule_key]['range'];
    }

    public function getRuleDiscount($rule_key)
    {
        return $this->rules[$rule_key]['discount'];
    }

    public function getRuleDiscountType($rule_key)
    {
        return $this->rules[$rule_key]['discount']['type'];
    }

    public function getRuleQualify($rule_key)
    {
        return $this->rules[$rule_key]['qualify'];
    }

    /**
     * Usage
     */
    public function setRuleUsageItemKey($rule_key, $item_key)
    {
        if (!in_array($item_key, $this->getRuleUsageItemKey($rule_key))) {
            array_push($this->rules[$rule_key]['usage']['item_keys'], $item_key);
        }
    }

    public function getRuleUsageItemKey($rule_key)
    {
        return $this->rules[$rule_key]['usage']['item_keys'];
    }

    public function getRuleUsageItems($rule_key)
    {
        return $this->getRuleUsageItemsByKey($this->getRuleUsageItemKey($rule_key));
    }

    public function getRuleUsageBenefit($rule_key)
    {
        return $this->rules[$rule_key]['usage']['benefit'];
    }

    public function setRuleUsageBenefit($rule_key, $benefit)
    {
        if (!$this->rules[$rule_key]['usage']['selected']) {
            $this->rules[$rule_key]['usage']['benefit'] = $benefit;
            $this->rules[$rule_key]['usage']['selected'] = true;
        }
    }

    public function getRuleUsageInRange($rule_key)
    {
        return $this->rules[$rule_key]['usage']['in_range'];
    }

    public function setRuleUsageInRange($rule_key)
    {
        //同组最高级的符合后其他变false
        $this->setRuleUsageNotInRangeByGroup($this->rules[$rule_key]['group']);
        $this->rules[$rule_key]['usage']['in_range'] = true;
    }

    protected function setRuleUsageNotInRangeByGroup($group)
    {
        foreach ($this->rules as $rule_key => $rule) {
            if (!is_null($group) && $group && $rule['group'] == $group) {
                $this->rules[$rule_key]['usage']['in_range'] = false;
            }
        }
    }

}

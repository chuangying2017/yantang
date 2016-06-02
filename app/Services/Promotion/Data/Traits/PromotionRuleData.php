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
            $this->rules[$key]['calculates'] = [
                'item_keys' => [],
                'discount' => 0,
                'success' => false,
                'need' => 0
            ];
        }
    }

    public function setRuleItemKey($rule_key, $item_key)
    {
        if (in_array($item_key, $this->rules[$rule_key]['calculates']['item_keys'])) {
            array_push($this->rules[$rule_key]['calculates']['item_keys'], $item_key);
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
    public function setRules($rules)
    {
        $this->rules = $rules;
        $this->initRuleData();
    }

    /**
     * 规则排序:
     * 1. 排他优先
     * 2. 分组和组内权重降序排序（分组优先,权重大优先）
     * 3. 规则对象范围小优先
     */
    public function sortRules()
    {
        
    }

    public function removeRule($rule_key)
    {
        unset($this->rules[$rule_key]);
    }

    public function addRule($rule)
    {
        array_push($this->rules, $rule);
    }

}

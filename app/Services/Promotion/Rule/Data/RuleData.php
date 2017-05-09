<?php namespace App\Services\Promotion\Rule\Data;

use App\Services\Promotion\PromotionProtocol;

class RuleData implements RuleDataContract {

    const KEY_OF_RULE_USABLE = 'usable';
    const KEY_OF_RULE_USING = 'using';
    const KEY_OF_RULE_CONFLICT_WITH_OTHER = 'conflict';
    const KEY_OF_RULE_RELATED = 'related';

    const VALUE_OF_RULE_YES = 1;
    const VALUE_OF_RULE_NO = 0;

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

    public function getAll($type = null)
    {
        return $this->rules;
    }

    public function getAllKeys()
    {
        return array_keys($this->rules);
    }

    public function getByGroup($group)
    {
        return array_filter($this->rules, function ($rule) use ($group) {
            return $rule['group'] == $group;
        });
    }

    public function setRule($rule_key)
    {
        $this->rule_key = $rule_key;
    }

    public function getRuleID()
    {
        return $this->getRule('id');
    }

    public function getPromotionID()
    {
        return $this->getRule('promotion_id');
    }

    public function getType()
    {
        return $this->getRule('promotion_type');
    }

    public function getQualification()
    {
        return $this->getRule('qualify');
    }

    public function getItems()
    {
        return $this->getRule('items');
    }

    public function getRange()
    {
        return $this->getRule('range');
    }

    public function getDiscount()
    {
        return $this->getRule('discount');
    }

    public function getWeight()
    {
        return $this->getRule('weight');
    }

    public function getMultiType()
    {
        return $this->getRule('multi');
    }

    public function getGroup()
    {
        return $this->getRule('group');
    }

    public function setRelated($item_keys)
    {
        $this->updateRule(self::KEY_OF_RULE_RELATED, $item_keys);
    }

    public function getRelatedItems()
    {
        return $this->getRule(self::KEY_OF_RULE_RELATED);
    }

    public function setUsable($rule_key = null)
    {
        return $this->updateRule(self::KEY_OF_RULE_USABLE, 1, $rule_key);
    }

    public function unsetUsable($rule_key = null)
    {
        $unsetRuleIsUsable = array_get($this->rules, $rule_key . '.' . self::KEY_OF_RULE_USABLE, null) ? true : false;

        if (!is_null($rule_key) && $rule_key != $this->rule_key && $unsetRuleIsUsable) {
            $this->updateRule(self::KEY_OF_RULE_CONFLICT_WITH_OTHER, $this->rule_key, $rule_key);
            $this->updateRule(self::KEY_OF_RULE_USABLE, 0, $rule_key);
        }
    }

    public function setUnusable()
    {
        $this->updateRule(self::KEY_OF_RULE_USABLE, 0 );
    }

    public function isUsable()
    {
        return $this->getRule(self::KEY_OF_RULE_USABLE);
    }

    public function unsetSameGroupUsable()
    {
        $rules = $this->getByGroup($this->getGroup());
        foreach ($rules as $rule_key => $rule) {
            if ($rule_key != $this->rule_key) {
                $this->unsetUsable($rule_key);
            }
        }

        return $this;
    }

    public function setConflictUsable()
    {
        foreach ($this->getConflicts() as $rule_key => $rule) {
            $this->setUsable($rule_key);
        }
        return $this;
    }

    public function setUsing()
    {
        return $this->updateRule(self::KEY_OF_RULE_USING, 1);
    }

    public function isUsing()
    {
        return $this->getRule(self::KEY_OF_RULE_USING);
    }

    public function unsetUsing()
    {
        return $this->updateRule(self::KEY_OF_RULE_USING, 0);
    }

    public function getRule($key = null)
    {
        return is_null($key) ? $this->rules[$this->rule_key] : array_get($this->rules, $this->rule_key . '.' . $key, null);
    }

    public function resetRule()
    {

    }

    public function setBenefit($value)
    {
        return $this->updateRule('benefit', $value);
    }

    public function unsetBenefit()
    {
        unset($this->rules[$this->rule_key]['benefit']);
        return $this;
    }

    public function getBenefit()
    {
        return $this->getRule('benefit');
    }

    public function unsetOtherUsable()
    {
        foreach ($this->getAllKeys() as $rule_key) {
            if ($rule_key !== $this->rule_key) {
                $this->unsetUsable($rule_key);
            }
        }
        return $this;
    }

    public function setMessage($message)
    {
        return $this->updateRule('message', $message);
    }

    public function getMessage()
    {
        return $this->getRule('message');
    }

    public function unsetRule()
    {
        unset($this->rules[$this->rule_key]);
        return $this;
    }

    protected function updateRule($key, $value, $rule_key = null)
    {
        if (is_null($rule_key)) {
            $this->rules[$this->rule_key][$key] = $value;
        } else {
            $this->rules[$rule_key][$key] = $value;
        }
        return $this;
    }

    public function getConflicts()
    {
        $conflict_rules = [];
        foreach ($this->getAll() as $rule_key => $rule) {
            if (array_get($rule, self::KEY_OF_RULE_CONFLICT_WITH_OTHER, null) === $this->rule_key) {
                $conflict_rules[$rule_key] = $rule;
            }
        }
        return $conflict_rules;
    }
}

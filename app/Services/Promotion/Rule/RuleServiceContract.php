<?php namespace App\Services\Promotion\Rule;

use App\Services\Promotion\Rule\Data\RuleDataContract;
use App\Services\Promotion\Support\PromotionAbleItemContract;

interface RuleServiceContract {

	/**
     * @return $this
     */
    public function filterQualify();
    
    /**
     * @return $this
     */
    public function filterRelate();

    /**
     * @return $this
     */
    public function filterUsable();

    /**
     * @param $rule_key
     * @return $this
     */
    public function using($rule_key);

    /**
     * @param $rule_key
     * @return $this
     */
    public function notUsing($rule_key);

    /**
     * @param PromotionAbleItemContract $items
     * @return mixed
     */
    public function setItems(PromotionAbleItemContract $items);
    
    /**
     * @param array $rules
     * @return $this
     */
    public function setRules(array $rules);

    /**
     * @return RuleDataContract
     */
    public function getRules();


}

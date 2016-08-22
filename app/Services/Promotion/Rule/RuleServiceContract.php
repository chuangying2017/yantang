<?php namespace App\Services\Promotion\Rule;

use App\Services\Promotion\Rule\Data\RuleDataContract;
use App\Services\Promotion\Support\PromotionAbleItemContract;
use App\Services\Promotion\Support\PromotionAbleUserContract;

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


    public function notUsingAll();

    /**
     * @param PromotionAbleItemContract $items
     * @return $this
     */
    public function setItems(PromotionAbleItemContract $items);


	/**
     * @param PromotionAbleUserContract $user
     * @return $this
     */
    public function setUser(PromotionAbleUserContract $user);
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

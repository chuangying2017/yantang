<?php namespace App\Services\Promotion\Rule;

use App\Repositories\Promotion\PromotionSupportRepositoryContract;
use App\Services\Promotion\Rule\Data\RuleDataContract;
use App\Services\Promotion\Support\PromotionAbleItemContract;

interface RuleServiceContract {

	/**
     * @return $this
     */
    public function filterRelate(PromotionAbleItemContract $items, PromotionSupportRepositoryContract $promotionSupport);

	/**
     * @return $this
     */
    public function filterUsable(PromotionAbleItemContract $items);

	/**
     * @param $rule_key
     * @return $this
     */
    public function using(PromotionAbleItemContract $items, $rule_key);

	/**
     * @param $rule_key
     * @return $this
     */
    public function notUsing(PromotionAbleItemContract $items, $rule_key);

	/**
     * @param $rules
     * @return $this
     */
    public function setRules($rules);

	/**
     * @return RuleDataContract
     */
    public function getRules();


}

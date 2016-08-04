<?php namespace App\Services\Promotion\Rule;

use App\Repositories\Promotion\PromotionSupportRepositoryContract;
use App\Services\Promotion\Rule\Data\RuleDataContract;
use App\Services\Promotion\Support\PromotionAbleItemContract;

interface RuleServiceContract {

    /**
     * @param PromotionSupportRepositoryContract $promotionSupport
     * @return $this
     */
    public function filterRelate(PromotionSupportRepositoryContract $promotionSupport);

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
     * @return mixed
     */
    public function setRules(array $rules);

    /**
     * @return RuleDataContract
     */
    public function getRules();


}

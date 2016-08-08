<?php namespace App\Services\Promotion;

use App\Repositories\Promotion\PromotionRepositoryContract;
use App\Services\Promotion\Rule\RuleServiceContract;
use App\Services\Promotion\Support\PromotionAbleItemContract;

abstract class PromotionServiceAbstract implements PromotionServiceContract {

    /**
     * @var RuleServiceContract
     */
    protected $ruleService;
    /**
     * @var PromotionRepositoryContract
     */
    protected $promotionRepo;

    /**
     * @var PromotionAbleItemContract
     */
    protected $items;


    /**
     * PromotionServiceAbstract constructor.
     * @param PromotionRepositoryContract $promotionRepo
     * @param RuleServiceContract $ruleService
     */
    public function __construct(PromotionRepositoryContract $promotionRepo, RuleServiceContract $ruleService)
    {
        $this->ruleService = $ruleService;
        $this->promotionRepo = $promotionRepo;
    }

    public function setItems(PromotionAbleItemContract $items)
    {
        $this->items = $items;
    }


    /**
     * 过滤相关
     * @param null $rules
     * @return $this
     */
    public function related($rules = null)
    {
        $rules_array = is_null($rules) ? $this->promotionRepo->getUsefulRules() : $rules;

        $this->ruleService->setRules($rules_array)->setItems($this->items);

        $this->ruleService->filterRelate();

        return $this;
    }

    /**
     *
     * @return $this
     */
    public function usable()
    {
        if (!$this->getRelateRules()) {
            $this->related();
        }

        $this->ruleService->filterUsable();

        return $this;
    }

    public function using($rule_key)
    {
        if (!$this->getRelateRules()) {
            $this->usable();
        }

        $this->ruleService->using($rule_key);

        return $this;
    }

    public function notUsing($rule_key)
    {
        if (!$this->getRelateRules()) {
            $this->usable();
        }

        $this->ruleService->notUsing($rule_key);

        return $this;
    }

    protected abstract function getRelateRules();

}

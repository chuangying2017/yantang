<?php namespace App\Services\Promotion;

use App\Repositories\Promotion\PromotionSupportRepositoryContract;
use App\Services\Promotion\Data\PromotionDataProtocol;
use App\Services\Promotion\Rule\Data\RuleDataContract;
use App\Services\Promotion\Rule\RuleServiceContract;
use App\Services\Promotion\Support\PromotionAbleItemContract;

abstract class PromotionServiceAbstract implements PromotionServiceContract {


    /**
     * @var RuleServiceContract
     */
    protected $ruleService;
    /**
     * @var PromotionSupportRepositoryContract
     */
    protected $promotionSupportRepo;

    /**
     * PromotionServiceAbstract constructor.
     * @param
     */
    public function __construct(PromotionSupportRepositoryContract $promotionSupportRepo, RuleServiceContract $ruleService)
    {
        $this->ruleService = $ruleService;
        $this->promotionSupportRepo = $promotionSupportRepo;
    }


    public function related(PromotionAbleItemContract $items, $rules = null)
    {
        $rules_array = is_null($rules) ? $this->promotionSupportRepo->getUsefulRules() : $rules;
        $this->ruleService->setRules($rules_array)->filterRelate($items, $this->promotionSupportRepo);
        return $items;
    }

    public function usable(PromotionAbleItemContract $items)
    {
        $rules = $this->getRelateRules($items);
        if (!$rules) {
            $this->related($items);
        } else {
            $this->ruleService->setRules($rules);
        }

        $this->ruleService->filterUsable($items);

        return $items;
    }

    public function using(PromotionAbleItemContract $items, $rule_key)
    {
        $rules = $this->getRelateRules($items);
        if (!$rules) {
            $this->usable($items);
        } else {
            $this->ruleService->setRules($rules);
        }

        $this->ruleService->setRules($rules)
            ->using($items, $rule_key);

        return $items;
    }


    public function notUsing(PromotionAbleItemContract $items, $rule_key)
    {
        $rules = $this->getRelateRules($items);
        if (!$rules) {
            $this->usable($items);
        } else {
            $this->ruleService->setRules($rules);
        }

        $this->ruleService->setRules($rules)
            ->notUsing($items, $rule_key);

        return $items;
    }

    protected abstract function getRelateRules(PromotionAbleItemContract $items);


}

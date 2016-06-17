<?php namespace App\Services\Promotion;


use App\Repositories\Promotion\Coupon\CouponRepositoryContract;
use App\Services\Promotion\Rule\RuleServiceContract;
use App\Services\Promotion\Support\PromotionAbleItemContract;
use App\Services\Promotion\Support\PromotionAbleUserContract;

class CouponService extends PromotionServiceAbstract implements PromotionDispatcher {

    public function __construct(CouponRepositoryContract $couponRepo, RuleServiceContract $ruleService)
    {
        parent::__construct($couponRepo, $ruleService);
    }

    public function dispatch(PromotionAbleUserContract $user, $promotion_id)
    {
        // TODO: Implement dispatch() method.
    }

    public function related(PromotionAbleItemContract $items, $rules = null)
    {
        $rules_array = is_null($rules) ? $this->promotionSupportRepo->getUsefulRules() : $rules;
        $this->ruleService->setRules($rules_array)->filterRelate($items, $this->promotionSupportRepo);
        return $items;
    }


    public function usable(PromotionAbleItemContract $items)
    {
        $rules = $items->getRelateCoupons();
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
        $rules = $items->getRelateCoupons();
        if (!$rules) {
            $this->usable($items);
        } else {
            $this->ruleService->setRules($rules);
        }

        $this->ruleService->setRules($rules)
            ->using($items, $rule_key);

        return $items;
    }


    public function notUse(PromotionAbleItemContract $items, $rule_key)
    {
        $rules = $items->getRelateCoupons();
        if (!$rules) {
            $this->usable($items);
        } else {
            $this->ruleService->setRules($rules);
        }

        $this->ruleService->setRules($rules)
            ->notUsing($items, $rule_key);

        return $items;
    }

}

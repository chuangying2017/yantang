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


    protected function getRelateRules()
    {

    }
}

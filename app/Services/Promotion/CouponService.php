<?php namespace App\Services\Promotion;


use App\Repositories\Promotion\Coupon\CouponRepositoryContract;
use App\Repositories\Promotion\TicketRepositoryContract;
use App\Services\Promotion\Rule\RuleServiceContract;
use App\Services\Promotion\Support\PromotionAbleItemContract;
use App\Services\Promotion\Support\PromotionAbleUserContract;
use Carbon\Carbon;

class CouponService extends PromotionServiceAbstract implements PromotionDispatcher {


    /**
     * @var TicketRepositoryContract
     */
    private $ticketRepo;

    /**
     * CouponService constructor.
     * @param CouponRepositoryContract $couponRepo
     * @param RuleServiceContract $ruleService
     * @param TicketRepositoryContract $ticketRepo
     */
    public function __construct(CouponRepositoryContract $couponRepo, RuleServiceContract $ruleService, TicketRepositoryContract $ticketRepo)
    {
        parent::__construct($couponRepo, $ruleService);
        $this->ticketRepo = $ticketRepo;
    }

    public function dispatch(PromotionAbleUserContract $user, $promotion_id)
    {
        $promotion = $this->promotionRepo->getPromotionWithDecodeRules($promotion_id);

        //非有效期内
        if ($promotion['start_time'] > Carbon::now() && $promotion['end_time'] < Carbon::now()) {
            $this->setErrorMessage('优惠券不在有效期内');
            return false;
        }

        if (empty($promotion['rules'])) {
            $this->setErrorMessage('来晚了');
            return false;
        }

        $this->ruleService->setUser($user)->setRules($promotion['rules']);
        $this->ruleService->filterQualify();

        //无资格领取
        if (empty($this->ruleService->getRules())) {
            $this->setErrorMessage('已经领取或不符合领取资格,领取失败');
            return false;
        }

        return $this->ticketRepo->createTicket($user->getUserId(), $promotion, true);
    }


}

<?php namespace App\Services\Order\Generator;

use App\Services\Promotion\CouponService;
use App\Services\Promotion\Support\PromotionAbleUserContract;

class UseCoupon extends GenerateHandlerAbstract {

    /**
     * @var CouponService
     */
    private $couponService;

    /**
     * @var PromotionAbleUserContract
     */
    private $userContract;

    /**
     * UseCoupon constructor.
     * @param CouponService $couponService
     * @param PromotionAbleUserContract $userContract
     */
    public function __construct(CouponService $couponService, PromotionAbleUserContract $userContract)
    {
        $this->couponService = $couponService;
        $this->userContract = $userContract;
    }

    public function handle(TempOrder $temp_order)
    {
        $ticket = $temp_order->getRequestPromotion();

        $this->userContract->setUser($temp_order->getUser());
        $temp_order->setRules($temp_order->getCoupons());
        $coupons = $temp_order->getCoupons();

        $rule_key = $this->findRuleKey($ticket, $coupons);

        if (!is_null($rule_key)) {
            $this->couponService
                ->setUser($this->userContract)
                ->setItems($temp_order);

            if (array_get($coupons, $rule_key . '.using', 0) == 1) {
                $success = $this->couponService->setNotUsing($rule_key);
            } else {
                $success = $this->couponService->setUsing($rule_key);
            }
            $temp_order->setCoupons($this->couponService->getRules());
        }

        return $this->next($temp_order);
    }

    protected function findRuleKey($ticket_id, $coupons)
    {
        foreach ($coupons as $key => $coupon) {
            if (array_get($coupon, 'ticket.id') == $ticket_id) {
                return $key;
            }
        }
        return null;
    }


}

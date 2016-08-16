<?php namespace App\Services\Order\Generator;

use App\Services\Promotion\CampaignService;
use App\Services\Promotion\CouponService;
use App\Services\Promotion\Support\PromotionAbleUserContract;

class CheckCoupon extends GenerateHandlerAbstract {

    /**
     * @var CouponService
     */
    private $couponService;

    /**
     * @var PromotionAbleUserContract
     */
    private $userContract;


    /**
     * CheckCoupon constructor.
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
        $this->userContract->setUser($temp_order->getUser());

        $this->couponService
            ->setUser($this->userContract)
            ->setItems($temp_order)
            ->checkUsable();

        $temp_order->setCoupons($this->couponService->getRules());

        return $this->next($temp_order);
    }


}

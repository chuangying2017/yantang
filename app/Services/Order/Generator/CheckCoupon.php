<?php namespace App\Services\Order\Generator;

use App\Services\Promotion\CouponService;

class CheckCoupon extends GenerateHandlerAbstract {

    /**
     * @var CouponService
     */
    private $couponService;


    /**
     * CheckCoupon constructor.
     * @param CouponService $couponService
     */
    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public function handle(TempOrder $temp_order)
    {
        $this->couponService->checkUsable($temp_order);
        return $this->next($temp_order);
    }
}

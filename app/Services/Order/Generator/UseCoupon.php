<?php namespace App\Services\Order\Generator;

use App\Services\Promotion\CouponService;

class UseCoupon extends GenerateHandlerAbstract{

    /**
     * @var CouponService
     */
    private $couponService;


    /**
     * UseCoupon constructor.
     * @param CouponService $couponService
     */
    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public function handle(TempOrder $temp_order)
    {
        #todo 使用优惠券
        #todo 如何传输参数 ?
        return $this->next($temp_order);
    }
}

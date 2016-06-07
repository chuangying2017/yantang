<?php namespace App\Services\Order\Generator;
class CheckCoupon extends GenerateHandlerAbstract {

    public function handle(TempOrder $temp_order)
    {
        #todo 检查可用的coupon

        return $this->next($temp_order);
    }
}

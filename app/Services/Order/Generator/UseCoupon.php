<?php namespace App\Services\Order\Generator;
class UseCoupon extends GenerateHandlerAbstract{

    public function handle(TempOrder $temp_order)
    {
        #todo 使用指定优惠券
        return $this->next($temp_order);
    }
}

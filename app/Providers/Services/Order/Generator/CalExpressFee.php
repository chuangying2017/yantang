<?php namespace App\Services\Order\Generator;
class CalExpressFee extends GenerateHandlerAbstract {


    public function handle(TempOrder $temp_order)
    {
        #todo 计算运费接口
        $express = 0;

        $temp_order->setExpressFee($express);
        return $this->next($temp_order);
    }
}

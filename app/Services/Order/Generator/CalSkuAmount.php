<?php namespace App\Services\Order\Generator;
class CalSkuAmount extends GenerateHandlerAbstract {


    public function handle(TempOrder $temp_order)
    {
        $sku_amount = 0;
        foreach ($temp_order->getSkus() as $sku) {
            $sku_amount = bcadd($sku_amount, $sku['price']);
        }

        $temp_order->setSkuAmount($sku_amount);
        $temp_order->setTotalAmount(bcadd($sku_amount, $temp_order->getExpressFee()));
        return $this->next($temp_order);
    }
}

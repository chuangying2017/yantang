<?php namespace App\Services\Order\Generator;
class CalSkuAmount extends GenerateHandlerAbstract {


    public function handle(TempOrder $temp_order)
    {
        $products_amount = 0;
        foreach ($temp_order->getSkus() as $key => $sku) {
            $sku_amount = bcmul($sku['price'], $sku['quantity'], 0);
            $products_amount = bcadd($sku_amount, $products_amount, 0);
            $temp_order->setSkuAmount($key, $sku_amount);
        }

        $temp_order->setProductsAmount($products_amount);

        $temp_order->setTotalAmount(bcadd($products_amount, $temp_order->getExpressFee()));

        return $this->next($temp_order);
    }
}

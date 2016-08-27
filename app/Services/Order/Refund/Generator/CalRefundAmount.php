<?php namespace App\Services\Order\Refund\Generator;
class CalRefundAmount extends RefundGenerateHandlerAbstract {

    public function handle(TempRefundOrder $temp_order)
    {
        $order_skus = $temp_order->getRefundSkus();

        $refund_amount = 0;
        $discount_amount = 0;
        foreach ($order_skus as $order_sku) {
            $discount_amount = $discount_amount + $order_sku['discount_amount'];
            $refund_amount = $refund_amount + ($order_sku['price'] * $order_sku['quantity']);
        }

        $temp_order->setRefundAmount($refund_amount);
        $temp_order->setDiscountAmount($discount_amount);

        return $this->next($temp_order);
    }
}

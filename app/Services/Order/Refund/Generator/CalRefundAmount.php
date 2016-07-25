<?php namespace App\Services\Order\Refund\Generator;
class CalRefundAmount extends RefundGenerateHandlerAbstract {

    public function handle(TempRefundOrder $temp_order)
    {
        $order_skus = $temp_order->getRefundSkus();
        $order = $temp_order->getReferOrder();

        $refund_amount = 0;
        foreach ($order_skus as $order_sku) {
            $refund_amount = $refund_amount + ($order_sku['price'] * $order_sku['quantity'] - $order_sku['discount_amount']);
        }

        $temp_order->setRefundAmount($refund_amount);
        $temp_order->setDiscountAmount($order['discount_amount']);

        return $this->next($temp_order);
    }
}

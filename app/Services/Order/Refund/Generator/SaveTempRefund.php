<?php namespace App\Services\Order\Refund\Generator;

use Cache;

class SaveTempRefund extends RefundGenerateHandlerAbstract {

    public function handle(TempRefundOrder $temp_order)
    {
        $temp_order_id = $temp_order->getTempOrderId();
        if (Cache::has($temp_order_id)) {
            Cache::forget($temp_order_id);
        }
        Cache::put($temp_order_id, $temp_order, 30);

        return $this->next($temp_order);
    }

}

<?php namespace App\Services\Order\Generator;

use Cache;

class SaveTempOrder extends GenerateHandlerAbstract {


    /**
     * @param TempOrder $temp_order
     */
    public function handle(TempOrder $temp_order)
    {
        $temp_order_id = $temp_order->getTempOrderId();
        if (Cache::has($temp_order_id)) {
            Cache::forget($temp_order_id);
        }
        Cache::put($temp_order_id, $temp_order, 30);

        return $this->next($temp_order);
    }
}

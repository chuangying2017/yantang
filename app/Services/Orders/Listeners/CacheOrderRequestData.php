<?php

namespace App\Services\Orders\Listeners;

use App\Services\Orders\Event\OrderInfoChange;
use App\Services\Orders\Helpers\OrderInfoHelpers;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CacheOrderRequestData {

    use OrderInfoHelpers;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderRequest $event
     * @return void
     */
    public function handle(OrderInfoChange $event)
    {
        $order_info = $event->order_info;
        $order_info['pay_amount'] = (int)bcsub($order_info['total_amount'], array_get($order_info, 'discount_fee', 0));
        $this->setOrder($order_info);
    }
}

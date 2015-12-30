<?php

namespace App\Services\Orders\Listeners;

use App\Services\Orders\Event\OrderConfirm;
use App\Services\Orders\OrderHandler;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderSpilt {

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
     * @param  OrderConfirm $event
     * @return void
     */
    public function handle(OrderConfirm $event)
    {
        $order_id = $event->order_id;

        OrderHandler::splitOrderByMerchant($order_id);
    }
}

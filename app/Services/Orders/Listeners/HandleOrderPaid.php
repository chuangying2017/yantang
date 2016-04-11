<?php

namespace App\Services\Orders\Listeners;

use App\Services\Orders\Event\OrderIsPaid;
use App\Services\Orders\OrderHandler;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleOrderPaid {

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
     * @param  OrderIsPaid $event
     * @return void
     */
    public function handle(OrderIsPaid $event)
    {
        $order_id = $event->order_id;

        OrderHandler::orderIsPaid($order_id);
    }
}

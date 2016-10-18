<?php

namespace App\Listeners\Order;

use App\Events\Order\FirstOrderIsPaid;
use App\Repositories\Order\Mark\OrderMarkRepo;
use App\Services\Order\OrderProtocol;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MarkFirstOrder {

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
     * @param  FirstOrderIsPaid $event
     * @return void
     */
    public function handle(FirstOrderIsPaid $event)
    {
        try {
            $order = $event->order;
            OrderMarkRepo::addMark($order['id'], OrderProtocol::ORDER_MARK_TYPE_OF_FIRST, OrderProtocol::ORDER_MARK_CONTENT_OF_FIRST);
        } catch (\Exception $e) {
            \Log::error($e);
        }
    }

}

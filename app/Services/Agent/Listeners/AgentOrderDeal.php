<?php

namespace App\Services\Agent\Listeners;

use App\Services\Agent\AgentService;
use App\Services\Orders\Event\OrderIsPaid;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AgentOrderDeal
{
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
     * @param  OrderIsPaid  $event
     * @return void
     */
    public function handle(OrderIsPaid $event)
    {
        $order_id = $event->order_id;

        AgentService::orderDeal($order_id);
    }
}

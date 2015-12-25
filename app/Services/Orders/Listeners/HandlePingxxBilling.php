<?php

namespace App\Services\Orders\Listeners;

use App\Services\Orders\Event\PingxxPaid;
use App\Services\Orders\OrderProtocol;
use App\Services\Orders\Payments\BillingManager;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandlePingxxBilling {

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
     * @param  PingxxPaid $event
     * @return void
     */
    public function handle(PingxxPaid $event)
    {
        $order_id = $event->order_id;
        $pingxx_payment_id = $event->pingxx_payment_id;

        BillingManager::mainBillingIsPaid($order_id, $pingxx_payment_id, OrderProtocol::RESOURCE_OF_PINGXX);
    }
}

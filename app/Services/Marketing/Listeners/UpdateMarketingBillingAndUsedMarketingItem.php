<?php

namespace App\Services\Marketing\Listeners;

use App\Services\Orders\Event\OrderIsPaid;
use App\Services\Orders\Payments\BillingManager;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateMarketingBillingAndUsedMarketingItem {

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

        BillingManager::marketingBillingPaid($order_id);

    }
}

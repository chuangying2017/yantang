<?php

namespace App\Services\Marketing\Listeners;

use App\Services\Orders\Event\OrderConfirm;
use App\Services\Orders\Payments\BillingManager;
use App\Services\Orders\Payments\BillingRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateMainBilling {

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
        $user_id = $event->user_id;
        $pay_amount = $event->pay_amount;
        $main_billing = BillingManager::storeOrderMainBilling($order_id, $user_id, $pay_amount);
    }
}

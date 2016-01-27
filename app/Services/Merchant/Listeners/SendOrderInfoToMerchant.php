<?php

namespace App\Services\Merchant\Listeners;

use App\Services\Merchant\MerchantService;
use App\Services\Orders\Event\OrderIsPaid;
use Faker\Provider\Uuid;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderInfoToMerchant {

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {


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
        MerchantService::sendNotify($order_id);
    }
}

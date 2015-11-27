<?php

namespace App\Services\Cart\Listeners;

use App\Services\Marketing\MarketingProtocol;
use App\Services\Marketing\MarketingRepository;
use App\Services\Orders\Event\OrderConfirm;
use App\Services\Orders\OrderProtocol;
use App\Services\Orders\Payments\PaymentRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderConfirmListener {

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

    }
}

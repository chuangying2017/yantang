<?php

namespace App\Services\Orders\Listeners;

use App\Services\Orders\Event\OrderConfirm;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderConfirmListener
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
     * @param  OrderConfirm  $event
     * @return void
     */
    public function handle(OrderConfirm $event)
    {
        //
    }
}

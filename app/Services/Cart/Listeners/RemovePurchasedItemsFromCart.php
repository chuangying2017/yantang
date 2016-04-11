<?php

namespace App\Services\Cart\Listeners;

use App\Services\Cart\CartService;
use App\Services\Orders\Event\OrderConfirm;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RemovePurchasedItemsFromCart {

    /**
     * Create the event listener.
     *
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
        if ( ! is_null($event->carts)) {
            $carts = $event->carts;
            CartService::remove($carts, $event->user_id);
        }
    }
}

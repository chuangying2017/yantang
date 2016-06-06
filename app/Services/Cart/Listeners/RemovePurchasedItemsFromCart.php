<?php

namespace App\Services\Cart\Listeners;

use App\Repositories\Cart\CartRepositoryContract;
use App\Services\Cart\CartService;
use App\Services\Orders\Event\OrderConfirm;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RemovePurchasedItemsFromCart {

    /**
     * @var CartRepositoryContract
     */
    private $cart;

    /**
     * Create the event listener.
     *
     */
    public function __construct(CartRepositoryContract $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Handle the event.
     *
     * @param  OrderConfirm $event
     * @return void
     */
    public function handle(OrderConfirm $event)
    {
        if (!is_null($event->carts)) {
            $this->cart->deleteMany($event->carts);
        }
    }
}

<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderIsCreated;
use App\Repositories\Cart\CartRepositoryContract;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RemoveCheckoutCartItems {

    /**
     * @var CartRepositoryContract
     */
    private $cartRepo;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(CartRepositoryContract $cartRepo)
    {
        $this->cartRepo = $cartRepo;
    }

    /**
     * Handle the event.
     *
     * @param  OrderIsCreated $event
     * @return void
     */
    public function handle(OrderIsCreated $event)
    {
        $temp_order = $event->temp_order;
        $this->cartRepo->deleteMany($temp_order->getCarts());
    }
}

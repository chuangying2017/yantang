<?php

namespace App\Services\Orders\Listeners;

use App\Services\Orders\Event\OrderRequest;
use App\Services\Orders\Helpers\OrderInfoHelpers;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CacheOrderRequestData
{
    use OrderInfoHelpers;
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
     * @param  OrderRequest  $event
     * @return void
     */
    public function handle(OrderRequest $event)
    {
        $order_info = $event->order_info;
        $this->setOrder($order_info);
    }
}

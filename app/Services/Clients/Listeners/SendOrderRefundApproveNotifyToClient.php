<?php

namespace App\Services\Clients\Listeners;

use App\Services\Orders\Event\OrderRefundApprove;
use App\Services\Orders\Event\OrderRefunding;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderRefundApproveNotifyToClient
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
     * @param  OrderRefundApprove  $event
     * @return void
     */
    public function handle(OrderRefunding $event)
    {
        //
    }
}

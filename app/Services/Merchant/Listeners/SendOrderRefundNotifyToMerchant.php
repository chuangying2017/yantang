<?php

namespace App\Services\Merchant\Listeners;

use App\Services\Orders\Event\OrderRefundApply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderRefundNotifyToMerchant
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
     * @param  OrderRefundApply  $event
     * @return void
     */
    public function handle(OrderRefundApply $event)
    {
        //
    }
}

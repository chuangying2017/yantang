<?php

namespace App\Listeners\Order;

use App\Services\Pay\Events\PingxxRefundPaymentIsFail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderRefundFail
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
     * @param  PingxxRefundPaymentIsFail  $event
     * @return void
     */
    public function handle(PingxxRefundPaymentIsFail $event)
    {
        //
    }
}

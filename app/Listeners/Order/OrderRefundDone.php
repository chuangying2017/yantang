<?php

namespace App\Listeners\Order;

use App\Services\Pay\Events\PingxxRefundPaymentIsDone;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderRefundDone
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
     * @param  PingxxRefundPaymentIsDone  $event
     * @return void
     */
    public function handle(PingxxRefundPaymentIsDone $event)
    {
        //
    }
}

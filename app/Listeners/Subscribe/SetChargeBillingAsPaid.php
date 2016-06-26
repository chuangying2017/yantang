<?php

namespace App\Listeners\Subscribe;

use App\Services\Pay\Events\PingxxPaymentIsPaid;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetChargeBillingAsPaid
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
     * @param  PingxxPaymentIsPaid  $event
     * @return void
     */
    public function handle(PingxxPaymentIsPaid $event)
    {
        //
    }
}

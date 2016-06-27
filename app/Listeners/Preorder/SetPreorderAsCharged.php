<?php

namespace App\Listeners\Preorder;

use App\Events\Preorder\ChargeBillingIsPaid;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetPreorderAsCharged
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
     * @param  ChargeBillingIsPaid  $event
     * @return void
     */
    public function handle(ChargeBillingIsPaid $event)
    {
        //
    }
}

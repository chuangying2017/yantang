<?php

namespace App\Listeners\Preorder;

use App\Events\Preorder\PaidPreorderBillingFail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetPreorderAsOut
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
     * @param  PaidPreorderBillingFail  $event
     * @return void
     */
    public function handle(PaidPreorderBillingFail $event)
    {
        //
    }
}

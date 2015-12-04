<?php

namespace App\Services\Orders\Listeners;

use App\Services\Orders\Event\PingxxPaid;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleOrderPaid
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
     * @param  PingxxPaid  $event
     * @return void
     */
    public function handle(PingxxPaid $event)
    {
        //
    }
}

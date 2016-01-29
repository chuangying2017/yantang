<?php

namespace App\Services\Agent\Listeners;

use App\Service\Agent\Event\NewAgent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateNewAgent
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
     * @param  NewAgent  $event
     * @return void
     */
    public function handle(NewAgent $event)
    {
        //
    }
}

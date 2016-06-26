<?php

namespace App\Listeners\Preorder;

use App\Events\Preorder\AssignIsConfirm;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetPreorderAsPending
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
     * @param  AssignIsConfirm  $event
     * @return void
     */
    public function handle(AssignIsConfirm $event)
    {
        //
    }
}

<?php

namespace App\Listeners\Preorder;

use App\Events\Preorder\StaffAssignIsCreate;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class IncreaseStaffWeeklySkus
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
     * @param  StaffAssignIsCreate  $event
     * @return void
     */
    public function handle(StaffAssignIsCreate $event)
    {
        //
    }
}

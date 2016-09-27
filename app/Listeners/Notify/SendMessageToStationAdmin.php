<?php

namespace App\Listeners\Notify;

use App\Events\Preorder\AssignIsReject;
use App\Events\Preorder\PreordersNotHandleInTime;
use App\Services\Notify\NotifyProtocol;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMessageToStationAdmin {
    
    /**
     * Handle the event.
     *
     * @param  PreordersNotHandleInTime $event
     * @return void
     */
    public function handle(PreordersNotHandleInTime $event)
    {
        //
    }

    public function preorderHandleOvertime(PreordersNotHandleInTime $event)
    {
        NotifyProtocol::notify(null, NotifyProtocol::NOTIFY_ACTION_ADMIN_PREORDER_IS_ONT_HANDLE_ON_TIME, null, null);
    }

    public function orderAssignIsReject(AssignIsReject $event)
    {
        NotifyProtocol::notify(null, NotifyProtocol::NOTIFY_ACTION_ADMIN_PREORDER_PREORDER_IS_REJECT, null, null);
    }


}

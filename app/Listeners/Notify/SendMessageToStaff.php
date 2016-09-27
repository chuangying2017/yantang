<?php

namespace App\Listeners\Notify;

use App\Events\Preorder\AssignIsAssigned;
use App\Services\Notify\NotifyProtocol;
use App\Services\Preorder\PreorderProtocol;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMessageToStaff {

    /**
     * Handle the event.
     *
     * @param  AssignIsAssigned $event
     * @return void
     */
    public function handle(AssignIsAssigned $event)
    {
        //
    }

    public function assigned(AssignIsAssigned $event)
    {
        $assign = $event->assign;

        $preorder = $assign->preorder;

        if ($preorder['status'] == PreorderProtocol::ORDER_STATUS_OF_SHIPPING) {
            NotifyProtocol::notify($preorder['staff_id'], NotifyProtocol::NOTIFY_ACTION_STAFF_NEW_ORDER, null, $preorder);
        }

    }


}

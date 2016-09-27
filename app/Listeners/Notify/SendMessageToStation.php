<?php

namespace App\Listeners\Notify;

use App\Events\Preorder\AssignIsCreate;
use App\Services\Notify\NotifyProtocol;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMessageToStation {

    /**
     * Handle the event.
     *
     * @param  AssignIsCreate $event
     * @return void
     */
    public function handle(AssignIsCreate $event)
    {
        //
    }

    public function newOrder(AssignIsCreate $event)
    {
        $assign = $event->assign;

        $preorder = $assign->preorder;

        NotifyProtocol::notify($preorder['station_id'], NotifyProtocol::NOTIFY_ACTION_STATION_NEW_ORDER, null, $preorder);
    }

}

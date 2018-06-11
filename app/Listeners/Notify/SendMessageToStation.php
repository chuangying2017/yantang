<?php

namespace App\Listeners\Notify;

use App\Events\Preorder\AssignIsCreate;
use App\Events\Preorder\PreorderIsPaid;
use App\Services\Notify\NotifyProtocol;
use App\Services\Preorder\PreorderProtocol;
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

    public function newOrder(PreorderIsPaid $event)
    {
        \Log::debug('test: SendMessageToStation@newOrder', [$event]);
        $preorder = $event->preorder;

        if (PreorderProtocol::preorderIsPaid($preorder['status'])) {

            NotifyProtocol::notify($preorder['station_id'], NotifyProtocol::NOTIFY_ACTION_STATION_NEW_ORDER, null, $preorder);

          //  NotifyProtocol::notify($preorder['user_id'],NotifyProtocol::NOTIFY_ACTION_CLIENT_COMMENT_IS_ALERT,null,$preorder);

        }
    }

    public function resignOrder(AssignIsCreate $event)
    {
        \Log::debug('test: SendMessageToStation@resignOrder', [$event]);

        $assign = $event->assign;
        $preorder = $assign->preorder;

        if (PreorderProtocol::preorderIsPaid($preorder['status'])) {

            NotifyProtocol::notify($preorder['station_id'], NotifyProtocol::NOTIFY_ACTION_STATION_NEW_ORDER, null, $preorder);

        }
    }


}

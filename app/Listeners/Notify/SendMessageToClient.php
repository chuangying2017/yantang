<?php

namespace App\Listeners\Notify;

use App\Events\Preorder\AssignIsAssigned;
use App\Repositories\Preorder\PreorderRepositoryContract;
use App\Services\Notify\NotifyProtocol;
use App\Services\Preorder\PreorderProtocol;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Toplan\PhpSms\Sms;

class SendMessageToClient {

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
            NotifyProtocol::notify($preorder['user_id'], NotifyProtocol::NOTIFY_ACTION_CLIENT_PREORDER_IS_ASSIGNED, null, $preorder);
        }

    }

    public function assignClientCommentNotify(AssignIsAssigned $assigned)
    {

        $assign = $assigned->assign;

        $preorder = $assign->preorder;

        if($preorder['status'] == PreorderProtocol::ORDER_STATUS_OF_ASSIGNING){
            file_put_contents('status.txt',$preorder['status']?:'node');
            NotifyProtocol::notify($preorder['user_id'], NotifyProtocol::NOTIFY_ACTION_CLIENT_COMMENT_IS_ALERT,null,$preorder);
        }

    }


}

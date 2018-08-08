<?php

namespace App\Listeners\Notify;

use App\Events\Preorder\AssignIsAssigned;
use App\Models\Subscribe\Preorder;
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

        $preorder = Preorder::find($preorder->id);

      //  file_put_contents('notifyClientMessage.txt',$preorder['status'].' '.date('Y-m-d H:i:s',time()) ." \r\n",FILE_APPEND);

        if ($preorder['status'] == PreorderProtocol::ORDER_STATUS_OF_SHIPPING) {
      //  file_put_contents('ClientTest.txt',$preorder['status'].' '.date('Y-m-d H:i:s',time()) . "\r\n",FILE_APPEND);
            NotifyProtocol::notify($preorder['user_id'], NotifyProtocol::NOTIFY_ACTION_CLIENT_PREORDER_IS_ASSIGNED, null, $preorder);
        }

    }

    public function assignClientCommentNotify(AssignIsAssigned $assigned)
    {

        $assign = $assigned->assign;

        $preorder = $assign->preorder;

        if($preorder['status'] == PreorderProtocol::ORDER_STATUS_OF_ASSIGNING){
            file_put_contents('status.txt',$preorder['status']?:'node');
            NotifyProtocol::notify($preorder['user_id'], NotifyProtocol::NOTIFY_ACTION_CLIENT_PREORDER_IS_ASSIGNED,null,$preorder);
        }

    }


}

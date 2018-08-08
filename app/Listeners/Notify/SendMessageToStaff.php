<?php

namespace App\Listeners\Notify;

use App\Events\Preorder\AssignIsAssigned;
use App\Models\Subscribe\Preorder;
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

        $preorder = Preorder::find($preorder->id);
      //  file_put_contents('notifyStaff.txt',$preorder['status'] ."\r\n",FILE_APPEND);
        if (PreorderProtocol::preorderIsPaid($preorder['status'])) {
        //    file_put_contents('StaffsTest.txt',$preorder. date('Y-m-d H:i:s',time()) . "\r\n",FILE_APPEND);

            NotifyProtocol::notify($preorder['staff_id'], NotifyProtocol::NOTIFY_ACTION_STAFF_NEW_ORDER, null, $preorder);

        }
        
    }


}

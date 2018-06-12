<?php

namespace App\Listeners\Comments;

use App\Services\Comments\Event\CommentIsCreated;
use App\Services\Notify\NotifyProtocol;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommentOperation
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
     * @param  CommentIsCreated  $event
     * @return void
     */
    public function handle(CommentIsCreated $event)
    {
        //
    }

    public function comment_success_notify_staff(CommentIsCreated $event){

        foreach ($event->preorders as $preorder){
                $preorder->comment_identify = 2;//comment get through
                $preorder->save();
                NotifyProtocol::notify($preorder['staff_id'],NotifyProtocol::NOTIFY_ACTION_CLIENT_COMMENT_IS_ALERT,null,$preorder);
        }

    }
}

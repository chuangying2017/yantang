<?php

namespace App\Listeners\Comments;

use App\Repositories\setting\SetMode;
use App\Services\Comments\Event\CommentIsCreated;
use App\Services\Notify\NotifyProtocol;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommentOperation
{
    protected $setting;

    /**
     * Create the event listener.
     *
     * @param SetMode $setMode
     */
    public function __construct(SetMode $setMode)
    {
        //
        $this->setting = $setMode;
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

                $preorder->user->wallet->increment('integral',array_get($this->setting->getSetting(2)['value'],'user_score','0'));

                NotifyProtocol::notify($preorder['staff_id'],NotifyProtocol::NOTIFY_ACTION_STAFF_COMMENT_IS_ALERT,null,$preorder);
        }

    }
}

<?php

namespace App\Listeners\Comments;

use App\Models\Integral\IntegralRecord;
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

        foreach ($event as $item){

                $item->preorders[0]->comment_identify = 2;//comment get through

                $item->preorders[0]->save();

                $setting = $this->setting->getSetting(2)['value'];

                $item->preorders[0]->user->wallet->increment('integral',array_get($setting,'user_score','0'));

                IntegralRecord::create([
                    'type_id' => $item->id,
                    'record_able' => get_class($item),
                    'user_id' => $item->preorders->first()['user_id'],
                    'name' => '评论获得积分',
                    'integral' => '+' . array_get($setting,'user_score','0')
                ]);

                NotifyProtocol::notify($item->preorders[0]['staff_id'],NotifyProtocol::NOTIFY_ACTION_STAFF_COMMENT_IS_ALERT,null,$item);
        }

    }
}

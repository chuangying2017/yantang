<?php

namespace App\Listeners\Notify;

use App\Events\Preorder\AssignIsReject;
use App\Events\Preorder\PreordersNotHandleInTime;
use App\Repositories\Backend\AccessProtocol;
use App\Repositories\Backend\User\UserContract;
use App\Services\Notify\NotifyProtocol;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Toplan\PhpSms\Sms;

class SendMessageToStationAdmin {

    /**
     * @var UserContract
     */
    private $userRepo;


    /**
     * Create the event listener.
     *
     * @param UserContract $userRepo
     */
    public function __construct(UserContract $userRepo)
    {
        $this->userRepo = $userRepo;
    }

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

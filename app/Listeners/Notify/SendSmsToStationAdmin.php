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

class SendSmsToStationAdmin {

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
        NotifyProtocol::sendMessage($this->getAdminStationPhone(), NotifyProtocol::SMS_TO_ADMIN_PREORDER_IS_ONT_HANDLE_ON_TIME);
    }

    public function orderAssignIsReject(AssignIsReject $event)
    {
        NotifyProtocol::sendMessage($this->getAdminStationPhone(), NotifyProtocol::SMS_TO_ADMIN_PREORDER_PREORDER_IS_REJECT);
    }

    protected function getAdminStationPhone()
    {
        return env('STATION_ADMIN_PHONE', '13580347020');
//        $users = $this->userRepo->getAllUsersByRole(AccessProtocol::ROLE_OF_STATION_ADMIN);
//
//        if (!is_null($users)) {
//            $user = $users->first();
//            return $user['phone'];
//        }
//
//        return null;
    }


}

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
        $this->sendMessage(NotifyProtocol::SMS_TO_ADMIN_PREORDER_IS_ONT_HANDLE_ON_TIME);
    }

    public function orderAssignIsReject(AssignIsReject $event)
    {
        $this->sendMessage(NotifyProtocol::SMS_TO_ADMIN_PREORDER_PREORDER_IS_REJECT);
    }

    protected function sendMessage($message)
    {
        try {
            $admin = $this->getAdminStationPhone();

            if (!is_null($admin)) {
                Sms::make()->to($admin['phone'])->content($message);
                return 1;
            }

            \Log::error('服务部管理员不存在');
            return 0;
        } catch (\Exception $e) {
            \Log::error($e);
        } finally {
            return 0;
        }
    }

    protected function getAdminStationPhone()
    {
        $users = $this->userRepo->getAllUsersByRole(AccessProtocol::ROLE_OF_STATION_ADMIN);

        if (!is_null($users)) {
            return $users->first();
        }

        return null;
    }


}

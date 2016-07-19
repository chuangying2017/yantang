<?php

namespace App\Listeners\Notify;

use App\Events\Preorder\AssignIsAssigned;
use App\Repositories\Preorder\PreorderRepositoryContract;
use App\Services\NotifyProtocol;
use App\Services\Preorder\PreorderProtocol;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Toplan\PhpSms\Sms;

class SendSmsToStaff {

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
     * @param  AssignIsAssigned $event
     * @return void
     */
    public function handle(AssignIsAssigned $event)
    {
        //
    }

    public function assigned(AssignIsAssigned $event, PreorderRepositoryContract $preorderRepo)
    {
        $assign = $event->assign;

        $preorder = $preorderRepo->get($assign['preorder_id'], false);

        if ($preorder['status'] == PreorderProtocol::ORDER_STATUS_OF_SHIPPING) {
            try {
                $phone = $preorder->staff->phone;
                Sms::make()->to($phone)->content(NotifyProtocol::SMS_TO_CLIENT_PREORDER_IS_ASSIGNED)->send();
            } catch (\Exception $e) {
                \Log::error($e);
            }
        }

    }
}

<?php

namespace App\Listeners\Notify;

use App\Events\Preorder\AssignIsCreate;
use App\Repositories\Preorder\PreorderRepositoryContract;
use App\Services\Notify\NotifyProtocol;
use App\Services\Preorder\PreorderProtocol;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Toplan\PhpSms\Sms;

class SendMessageToStation {

    /**
     * @var PreorderRepositoryContract
     */
    private $preorderRepo;

    /**
     * Create the event listener.
     *
     * @param PreorderRepositoryContract $preorderRepo
     */
    public function __construct(PreorderRepositoryContract $preorderRepo)
    {
        $this->preorderRepo = $preorderRepo;
    }

    /**
     * Handle the event.
     *
     * @param  AssignIsCreate $event
     * @return void
     */
    public function handle(AssignIsCreate $event)
    {
        //
    }

    public function newOrder(AssignIsCreate $event)
    {
        $assign = $event->assign;

        $preorder = $this->preorderRepo->get($assign['preorder_id']);

        NotifyProtocol::notify($preorder['station_id'], NotifyProtocol::NOTIFY_ACTION_STATION_NEW_ORDER, null, $preorder);
    }


}

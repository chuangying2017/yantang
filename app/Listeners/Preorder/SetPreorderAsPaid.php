<?php

namespace App\Listeners\Preorder;

use App\Events\Order\OrderIsPaid;
use App\Repositories\Preorder\PreorderRepositoryContract;
use App\Services\Notify\NotifyProtocol;
use App\Services\Order\OrderProtocol;
use App\Services\Preorder\PreorderProtocol;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Toplan\PhpSms\Sms;

class SetPreorderAsPaid {

    /**
     * @var PreorderRepositoryContract
     */
    private $preorderRepo;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(PreorderRepositoryContract $preorderRepo)
    {
        $this->preorderRepo = $preorderRepo;
    }

    /**
     * Handle the event.
     *
     * @param  OrderIsPaid $event
     * @return void
     */
    public function handle(OrderIsPaid $event)
    {
        $order = $event->order;

        if ($order['order_type'] == OrderProtocol::ORDER_TYPE_OF_SUBSCRIBE) {
            $preorder = $this->preorderRepo->updatePreorderStatusByOrder($order['id'], PreorderProtocol::ORDER_STATUS_OF_ASSIGNING);

            if ($preorder['status'] !== PreorderProtocol::ORDER_STATUS_OF_UNPAID) {

                $preorder->load('station');

                $phone = $preorder['station']['phone'];

                $result = Sms::make()->to($phone)->content(NotifyProtocol::SMS_TO_STATION_NEW_ORDER)->send();
            }
        }
    }
}

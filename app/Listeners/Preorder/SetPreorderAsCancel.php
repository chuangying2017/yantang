<?php

namespace App\Listeners\Preorder;

use App\Events\Order\OrderIsCancel;
use App\Repositories\Preorder\PreorderRepositoryContract;
use App\Services\Order\OrderProtocol;
use App\Services\Preorder\PreorderProtocol;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetPreorderAsCancel {

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
     * @var PreorderRepositoryContract
     */
    private $preorderRepo;


    /**
     * @param OrderIsCancel $event
     */
    public function handle(OrderIsCancel $event)
    {
        $order = $event->order;
        if ($order['order_type'] == OrderProtocol::ORDER_TYPE_OF_SUBSCRIBE) {
            $this->preorderRepo->updatePreorderStatusByOrder($order['id'], PreorderProtocol::ORDER_STATUS_OF_CANCEL);
        }
    }


}

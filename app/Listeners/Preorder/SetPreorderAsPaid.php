<?php

namespace App\Listeners\Preorder;

use App\Events\Order\OrderIsPaid;
use App\Repositories\Preorder\PreorderRepositoryContract;
use App\Services\Preorder\PreorderProtocol;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        $this->preorderRepo->updatePreorderStatusByOrder($order['id'], PreorderProtocol::ORDER_STATUS_OF_ASSIGNING);
    }
}

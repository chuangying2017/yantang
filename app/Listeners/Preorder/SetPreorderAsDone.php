<?php

namespace App\Listeners\Preorder;

use App\Events\Preorder\PreorderSkusOut;
use App\Repositories\Order\PreorderOrderRepository;
use App\Repositories\Preorder\PreorderRepositoryContract;
use App\Services\Preorder\PreorderProtocol;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetPreorderAsDone {

    /**
     * @var PreorderOrderRepository
     */
    private $orderRepo;
    /**
     * @var PreorderRepositoryContract
     */
    private $preorderRepo;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(PreorderOrderRepository $orderRepo, PreorderRepositoryContract $preorderRepo)
    {
        $this->orderRepo = $orderRepo;
        $this->preorderRepo = $preorderRepo;
    }

    /**
     * Handle the event.
     *
     * @param  PreorderSkusOut $event
     * @return void
     */
    public function handle(PreorderSkusOut $event)
    {
        $preorder = $event->preorder;

        $this->preorderRepo->updatePreorderStatus($preorder['id'], PreorderProtocol::ORDER_STATUS_OF_DONE);
        $this->orderRepo->updateOrderStatusAsDeliverDone($preorder['order_id']);
    }
}

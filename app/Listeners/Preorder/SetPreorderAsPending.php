<?php

namespace App\Listeners\Preorder;

use App\Events\Preorder\AssignIsAssigned;
use App\Repositories\Order\PreorderOrderRepository;
use App\Repositories\Preorder\PreorderRepositoryContract;
use App\Services\Preorder\PreorderProtocol;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetPreorderAsPending {

    /**
     * @var PreorderRepositoryContract
     */
    private $orderRepo;
    /**
     * @var PreorderOrderRepository
     */
    private $preorderOrderRepository;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(PreorderRepositoryContract $orderRepo, PreorderOrderRepository $preorderOrderRepository)
    {
        $this->orderRepo = $orderRepo;
        $this->preorderOrderRepository = $preorderOrderRepository;
    }

    /**
     * Handle the event.
     *
     * @param  AssignIsAssigned $event
     * @return void
     */
    public function handle(AssignIsAssigned $event)
    {
        $assign = $event->assign;
        $preorder = $this->orderRepo->updatePreorderStatus($assign['preorder_id'], PreorderProtocol::ORDER_STATUS_OF_SHIPPING);
        $this->orderRepo->updatePreorderAssign($assign['preorder_id'], null, $assign['staff_id']);
        $this->preorderOrderRepository->updateOrderStatusAsDeliver($preorder['order_id']);
    }
}
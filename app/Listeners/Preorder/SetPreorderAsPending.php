<?php

namespace App\Listeners\Preorder;

use App\Events\Preorder\AssignIsAssigned;
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
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(PreorderRepositoryContract $orderRepo)
    {
        $this->orderRepo = $orderRepo;
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
        $this->orderRepo->updatePreorderStatus($assign['preorder_id'], PreorderProtocol::ORDER_STATUS_OF_SHIPPING);
        $this->orderRepo->updatePreorderAssign($assign['preorder_id'], null, $assign['staff_id']);
    }
}

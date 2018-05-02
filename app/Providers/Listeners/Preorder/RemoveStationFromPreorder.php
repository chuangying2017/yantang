<?php

namespace App\Listeners\Preorder;

use App\Events\Preorder\AssignIsReject;
use App\Repositories\Preorder\PreorderRepositoryContract;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RemoveStationFromPreorder {

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
     * @param  AssignIsReject $event
     * @return void
     */
    public function handle(AssignIsReject $event)
    {
        $assign = $event->assign;
        $this->preorderRepo->updatePreorderAssign($assign['preorder_id'], 0, 0);
    }
}

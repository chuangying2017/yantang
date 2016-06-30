<?php

namespace App\Listeners\Preorder;

use App\Events\Preorder\AssignIsCreate;
use App\Repositories\Preorder\PreorderRepositoryContract;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetStationForPreorder {

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
     * @param  AssignIsCreate $event
     * @return void
     */
    public function handle(AssignIsCreate $event)
    {
        $assign = $event->assign;
        $this->preorderRepo->updatePreorderAssign($assign->preorder_id, $assign->station_id, $assign->staff_id);
    }
}

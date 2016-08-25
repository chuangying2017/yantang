<?php

namespace App\Listeners\Preorder;

use App\Events\Preorder\PaidPreorderBillingFail;
use App\Services\Preorder\PreorderManageServiceContract;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetPreorderAsOut {

    /**
     * @var PreorderManageServiceContract
     */
    private $preorderManager;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(PreorderManageServiceContract $preorderManager)
    {
        $this->preorderManager = $preorderManager;
    }

    /**
     * Handle the event.
     *
     * @param  PaidPreorderBillingFail $event
     * @return void
     */
    public function handle(PaidPreorderBillingFail $event)
    {
        $billing = $event->billing;

        $this->preorderManager->needCharge($billing['user_id']);
    }
}

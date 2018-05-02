<?php

namespace App\Listeners\Preorder;

use App\Events\Preorder\ChargeBillingIsPaid;
use App\Services\Preorder\PreorderManageServiceContract;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetPreorderAsCharged {

    /**
     * @var PreorderManageServiceContract
     */
    private $preorderManage;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(PreorderManageServiceContract $preorderManage)
    {
        $this->preorderManage = $preorderManage;
    }

    /**
     * Handle the event.
     *
     * @param  ChargeBillingIsPaid $event
     * @return void
     */
    public function handle(ChargeBillingIsPaid $event)
    {
        $billing = $event->billing;
        $this->preorderManage->charged($billing['user_id']);
    }
}

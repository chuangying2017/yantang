<?php

namespace App\Services\Agent\Listeners;

use App\Services\Agent\AgentOrderService;
use App\Services\Orders\Event\OrderRefundApprove;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CancelAwardAgent {

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderRefundApprove $event
     * @return void
     */
    public function handle(OrderRefundApprove $event)
    {
        $refund_order = $event->refund_order;

        AgentOrderService::refund($refund_order['order_id'], $refund_order['amount']);
    }
}

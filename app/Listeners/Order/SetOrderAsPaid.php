<?php

namespace App\Listeners\Order;

use App\Events\Order\MainBillingIsPaid;
use App\Services\Order\OrderManageService;
use App\Services\Order\OrderProtocol;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetOrderAsPaid {

    /**
     * @var OrderManageService
     */
    private $orderManageService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(OrderManageService $orderManageService)
    {
        $this->orderManageService = $orderManageService;
    }

    /**
     * Handle the event.
     *
     * @param  MainBillingIsPaid $event
     * @return void
     */
    public function handle(MainBillingIsPaid $event)
    {
        $billing = $event->billing;
        if ($billing['pay_type'] == OrderProtocol::BILLING_TYPE_OF_MONEY) {
            $this->orderManageService->orderPaid($billing['order_id']);
        }

    }
}

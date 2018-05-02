<?php

namespace App\Listeners\Order;

use App\Events\Order\FirstOrderIsPaid;
use App\Events\Order\OrderIsPaid;
use App\Services\Order\OrderManageService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckIfFirstOrder {

    /**
     * Create the event listener.
     *
     * @param OrderManageService $orderManageService
     */
    public function __construct(OrderManageService $orderManageService)
    {
        $this->orderManageService = $orderManageService;
    }

    /**
     * Handle the event.
     *
     * @param  OrderIsPaid $event
     * @return void
     */
    public function handle(OrderIsPaid $event)
    {
        \Log::debug('test: CheckIfFirstOrder', [$event]);

        $order = $event->order;
        if ($this->orderManageService->orderIsFirstPaid($order)) {
            event(new FirstOrderIsPaid($order));
        }
    }

    /**
     * @var OrderManageService
     */
    private $orderManageService;
}

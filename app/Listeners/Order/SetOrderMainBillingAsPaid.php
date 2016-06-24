<?php

namespace App\Listeners\Order;

use App\Events\Order\MainBillingIsPaid;
use App\Repositories\Billing\OrderBillingRepository;
use App\Repositories\Order\ClientOrderRepository;
use App\Services\Billing\BillingProtocol;
use App\Services\Order\OrderManageService;
use App\Services\Pay\Events\PingxxPaymentIsPaid;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetOrderMainBillingAsPaid {

    /**
     * @var OrderBillingRepository
     */
    private $orderBillingRepository;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(OrderBillingRepository $orderBillingRepository)
    {
        $this->orderBillingRepository = $orderBillingRepository;
    }

    /**
     * Handle the event.
     *
     * @param  PingxxPaymentIsPaid $event
     * @return void
     */
    public function handle(PingxxPaymentIsPaid $event)
    {
        $pingxx_payment = $event->payment;

        if ($pingxx_payment['billing_type'] == BillingProtocol::BILLING_TYPE_OF_ORDER_BILLING) {
            $billing = $this->orderBillingRepository->updateAsPaid($pingxx_payment['billing_id'], $pingxx_payment['channel']);
            event(new MainBillingIsPaid($billing));
        }
    }
}

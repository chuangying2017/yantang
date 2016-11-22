<?php

namespace App\Listeners\Order;

use App\Events\Order\MainBillingIsPaid;
use App\Services\Billing\BillingProtocol;
use App\Services\Order\Checkout\OrderCheckoutService;
use App\Services\Pay\Events\PingxxPaymentIsPaid;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetOrderMainBillingAsPaid {


    /**
     * @var OrderCheckoutService
     */
    private $orderCheckoutService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(OrderCheckoutService $orderCheckoutService)
    {
        $this->orderCheckoutService = $orderCheckoutService;
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
            $billing = $this->orderCheckoutService->billingPaid($pingxx_payment);
        }
    }
}

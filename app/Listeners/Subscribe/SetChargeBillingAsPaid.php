<?php

namespace App\Listeners\Subscribe;

use App\Events\Preorder\ChargeBillingIsPaid;
use App\Repositories\Billing\ChargeBillingRepository;
use App\Services\Billing\BillingProtocol;
use App\Services\Pay\Events\PingxxPaymentIsPaid;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetChargeBillingAsPaid {

    /**
     * @var ChargeBillingRepository
     */
    private $billingRepo;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ChargeBillingRepository $billingRepo)
    {
        $this->billingRepo = $billingRepo;
    }

    /**
     * Handle the event.
     *
     * @param  PingxxPaymentIsPaid $event
     * @return void
     */
    public function handle(PingxxPaymentIsPaid $event)
    {
        \Log::debug('test: SetChargeBillingAsPaid', [$event]);

        $pingxx_payment = $event->payment;
        if ($pingxx_payment['billing_type'] == BillingProtocol::BILLING_TYPE_OF_RECHARGE_BILLING) {
            $billing = $this->billingRepo->updateAsPaid($pingxx_payment['billing_id']);
            event(new ChargeBillingIsPaid($billing));
        }
    }
}

<?php

namespace App\Listeners\Billing;

use App\Services\Pay\Events\PingxxRefundPaymentIsDone;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateBillingRefundAmount {

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
     * @param  PingxxRefundPaymentIsDone $event
     * @return void
     */
    public function handle(PingxxRefundPaymentIsDone $event)
    {
        \Log::debug('test: UpdateBillingRefundAmount', [$event]);

        $refund_payment = $event->refund_payment;

        $refund_payment->payment()->update(['refunded' => 1]);
    }
}

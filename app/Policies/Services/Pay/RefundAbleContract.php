<?php namespace App\Services\Pay;

use App\Services\Billing\RefundBillingContract;

interface RefundAbleContract {

    public function refundable(RefundBillingContract $billing);

    public function refund(RefundBillingContract $billing);

}

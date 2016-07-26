<?php namespace App\Services\Pay;
interface ThirdPartyPayContract extends PayableContract {

    public function getChargeAndSetIfPaid($payment);

    public function checkPaymentPaid($payment);

    public function checkChargeIsPaid($charge);

    public function paid($charge);

    public function setChannel($channel);

    public function checkBillingIsPaid($billing_id, $billing_type);
}

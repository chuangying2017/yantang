<?php namespace App\Services\Pay;
interface ThirdPartyPayContract {

    public function getChargeAndSetIfPaid($payment);

    public function checkPaymentPaid($payment);

}

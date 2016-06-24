<?php namespace App\Services\Pay;
interface ThirdPartyPayContract {

    public function getChargeAndSetIfPaid($payment);

    public function checkPaymentPaid($payment);

    public function checkChargeIsPaid($charge);

    public function paid($charge);

}

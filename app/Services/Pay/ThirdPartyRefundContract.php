<?php namespace App\Services\Pay;
interface ThirdPartyRefundContract {

    public function checkPaymentSucceed($payment);

    public function checkRefundChargeIsSucceed($charge);

    public function succeed($charge);

}

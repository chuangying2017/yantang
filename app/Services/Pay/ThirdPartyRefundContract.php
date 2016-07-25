<?php namespace App\Services\Pay;
interface ThirdPartyRefundContract extends RefundAbleContract{

    public function checkPaymentSucceed($payment);

    public function checkRefundChargeIsSucceed($charge);

    public function succeed($charge);

}

<?php namespace App\Services\Pay;
interface ThirdPartyRefundContract extends RefundAbleContract{

    public function checkPaymentSucceed($payment);

    public function checkRefundChargeIsDone($refund);

    public function succeed($refund);

}

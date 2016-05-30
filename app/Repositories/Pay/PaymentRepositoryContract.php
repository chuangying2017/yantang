<?php namespace App\Repositories\Pay;

interface PaymentRepositoryContract {

    public function createPayment($charge, $billing_id, $billing_type);

    public function getPaymentByBilling($billing_id, $billing_type, $channel);

    public function setPaymentAsPaid($payment_no, $transaction_no);

    public function deletePayment($payment_no);

    public function getPayment($payment_no);

    public function getPayChannel($payment_no);

    public function getPayType($payment_no);

}

<?php namespace App\Repositories\Pay;
interface RefundPaymentRepositoryContract {

    public function createRefundPayment($payment, $amount, $billing_id);

    public function getRefundPayment($payment_no);

    public function getRefundPaymentByBilling($billing_id, $billing_type);

    public function getRefundPaymentsByPayment($payment_id);

    public function updateRefundPaymentAsDone($payment_no);

    public function updateRefundPaymentAsFail($payment_no, $failure_code, $failure_msg);

}

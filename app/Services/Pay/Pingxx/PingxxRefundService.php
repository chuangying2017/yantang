<?php namespace App\Services\Pay\Pingxx;

use App\Repositories\Pay\Pingxx\PingxxRefundPaymentRepository;
use App\Services\Billing\RefundBillingContract;
use App\Services\Pay\Events\PingxxRefundPaymentIsSucceed;
use App\Services\Pay\ThirdPartyRefundContract;

class PingxxRefundService implements ThirdPartyRefundContract {

    /**
     * @var PingxxRefundPaymentRepository
     */
    private $paymentRepository;

    /**
     * PingxxRefundService constructor.
     * @param PingxxRefundPaymentRepository $paymentRepository
     */
    public function __construct(PingxxRefundPaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function refundable(RefundBillingContract $billing)
    {
        $refer_billing = $billing->getReferBilling();

        $refundable_amount = $refer_billing->getAmount() - $refer_billing->getRefundedAmount();

        return ($refundable_amount >= $billing->getAmount());
    }

    public function refund(RefundBillingContract $billing)
    {
        if ($this->refundable($billing)) {

            $refer_billing = $billing->getReferBilling();

            $payment = $this->paymentRepository->createRefundPayment(
                $refer_billing->getPayment(),
                $billing->getAmount(),
                $billing->getID()
            );

            return $payment;
        }

        throw new \Exception('退款失败');
    }

    public function checkPaymentSucceed($payment)
    {
        $payment = $this->paymentRepository->getRefundPayment($payment);
        if ($payment['status'] == PingxxProtocol::STATUS_OF_REFUND_SUCCESS) {
            return $payment;
        }

        return false;
    }

    public function checkRefundChargeIsSucceed($refund_charge)
    {
        return $refund_charge->succeed;
    }

    public function succeed($refund_charge)
    {
        if ($this->checkRefundChargeIsSucceed($refund_charge)) {

            $payment = $this->paymentRepository->getRefundPayment($refund_charge->order_no);
            if ($payment['status'] == PingxxProtocol::STATUS_OF_REFUND_SUCCESS) {
                return true;
            }

            $payment = $this->paymentRepository->updateRefundPaymentAsDone($payment);

            event(new PingxxRefundPaymentIsSucceed($payment));

            return true;
        }

        return false;
    }


}

<?php namespace App\Services\Pay\Pingxx;

use App\Repositories\Pay\Pingxx\PingxxPaymentRefundPaymentRepository;
use App\Services\Billing\BillingContract;
use App\Services\Billing\RefundBillingContract;
use App\Services\Pay\RefundAbleContract;
use App\Services\Pay\ThirdPartyRefundContract;

class PingxxRefundService implements RefundAbleContract, ThirdPartyRefundContract {

    /**
     * @var BillingContract
     */
    private $billingContract;

    /**
     * @var PingxxPaymentRefundPaymentRepository
     */
    private $paymentRepository;

    /**
     * PingxxRefundService constructor.
     * @param BillingContract $billingContract
     * @param PingxxPaymentRefundPaymentRepository $paymentRepository
     */
    public function __construct(BillingContract $billingContract, PingxxPaymentRefundPaymentRepository $paymentRepository)
    {
        $this->billingContract = $billingContract;
        $this->paymentRepository = $paymentRepository;
    }

    public function refundable(RefundBillingContract $billing)
    {
        $refer_billing = $billing->getReferBilling();

        $this->billingContract->setID($refer_billing);

        $refundable_amount = $this->billingContract->getAmount() - $this->billingContract->getRefundedAmount();

        return ($refundable_amount >= $billing->getAmount());
    }

    public function refund(RefundBillingContract $billing)
    {
        if ($this->refundable($billing)) {

            $refer_billing = $billing->getReferBilling();

            $payment = $this->paymentRepository->createRefundPayment(
                $this->billingContract->setID($refer_billing)->getPayment(),
                $billing->getAmount(),
                $billing->getID()
            );

            return $payment;
        }

        throw new \Exception('退款失败');
    }

    public function checkPaymentSucceed($payment)
    {
        // TODO: Implement checkPaymentSucceed() method.
    }

    public function checkRefundChargeIsSucceed($charge)
    {
        // TODO: Implement checkRefundChargeIsSucceed() method.
    }

    public function succeed($charge)
    {

    }


}

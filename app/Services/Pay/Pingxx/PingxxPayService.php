<?php namespace App\Services\Pay\Pingxx;

use App\Repositories\Pay\Pingxx\PingxxPaymentRepository;
use App\Services\Billing\BillingContract;
use App\Services\Pay\Events\PingxxPaymentIsPaid;
use App\Services\Pay\PayableContract;

class PingxxPayService implements PayableContract {

    protected $channel;

    /**
     * @var PingxxPaymentRepository
     */
    private $paymentRepository;

    /**
     * PingxxPayService constructor.
     * @param PingxxPaymentRepository $paymentRepository
     */
    public function __construct(PingxxPaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function pay(BillingContract $billing)
    {

        if ($billing->isPaid()) {
            throw new \Exception('订单已支付,无需重复支付');
        }

        $payment = $this->paymentRepository->getPaymentByBilling($billing->getID(), $billing->getType(), $this->getChannel());


        if ($payment) {

            $charge = $this->paymentRepository->getCharge($payment['charge_id']);

            if ($this->paymentRepository->isPaid($charge)) {
                $payment = $this->paymentRepository->setPaymentAsPaid($payment, $this->paymentRepository->getTransaction($charge));
                event(new PingxxPaymentIsPaid($payment));
            }

        } else {
            $charge = $this->paymentRepository->createCharge($billing->getAmount(), $billing->getOrderNo(), $this->getChannel());
            $payment = $this->paymentRepository->createPayment($charge, $billing->getID(), $billing->getType());
        }

        return $charge;
    }


    public function enough($need_amount)
    {
        return true;
    }

    /**
     * @param mixed $channel
     * @return PingxxPayService
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getChannel()
    {
        return $this->channel;
    }

}

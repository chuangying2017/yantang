<?php namespace App\Services\Pay\Pingxx;

use App\Repositories\Pay\Pingxx\PingxxPaymentRepository;
use App\Services\Billing\BillingContract;
use App\Services\Order\OrderProtocol;
use App\Services\Pay\Events\PingxxPaymentIsPaid;
use App\Services\Pay\PayableContract;
use App\Services\Pay\ThirdPartyPayContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PingxxPayService implements PayableContract, ThirdPartyPayContract {

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
            throw new \Exception('订单已支付,无需重复支付', 402);
        }

        $payment = $this->paymentRepository->getPaymentByBilling($billing->getID(), $billing->getType(), $this->getChannel());

        if ($payment) {
            $charge = $this->getChargeAndSetIfPaid($payment);
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

    public function getChargeAndSetIfPaid($payment)
    {
        $payment = $this->paymentRepository->getPayment($payment);
        $charge = $this->paymentRepository->getCharge($payment['charge_id']);

        if ($payment['paid']) {
            event(new PingxxPaymentIsPaid($payment));
        } else {
            $this->paid($charge);
        }

        return $charge;
    }

    public function paid($charge)
    {
        if ($this->checkChargeIsPaid($charge)) {
            $payment = $this->paymentRepository->getPayment($this->paymentRepository->getChargePayment($charge));
            if ($payment['paid']) {
                return true;
            }

            $payment = $this->paymentRepository->setPaymentAsPaid($payment, $this->paymentRepository->getChargeTransaction($charge));
            event(new PingxxPaymentIsPaid($payment));

            return true;
        }

        return false;
    }

    public function checkChargeIsPaid($charge)
    {
        $charge = $this->paymentRepository->getCharge($charge);

        if (config('services.pingxx.live')) {
            return $charge->paid && $charge->livemode;
        }

        return $charge->paid;
    }

    public function checkPaymentPaid($payment)
    {
        $payment = $this->paymentRepository->getPayment($payment);
        if ($payment['paid']) {
            return $payment;
        }

        return false;
    }


}

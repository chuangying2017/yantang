<?php namespace App\Repositories\Pay\Pingxx;

use App\Models\Pay\PingxxPaymentRefund;
use App\Repositories\Pay\RefundPaymentRepositoryContract;
use App\Repositories\Pay\RefundRepositoryContract;
use App\Services\Pay\Events\PingxxRefundPaymentIsDone;
use App\Services\Pay\Events\PingxxRefundPaymentIsFail;
use App\Services\Pay\Events\PingxxRefundPaymentIsSucceed;
use App\Services\Pay\Pingxx\PingxxProtocol;
use Pingpp\Refund;

class PingxxRefundPaymentRepository implements RefundRepositoryContract, RefundPaymentRepositoryContract {

    /**
     * @var PingxxPaymentRepository
     */
    private $pingxxPayment;

    /**
     * PingxxPaymentRefundPaymentRepository constructor.
     * @param PingxxPaymentRepository $pingxxPayment
     */
    public function __construct(PingxxPaymentRepository $pingxxPayment)
    {
        $this->pingxxPayment = $pingxxPayment;
    }

    public function createRefund($charge_id, $amount, $desc = '退款')
    {
        $charge = $this->pingxxPayment->getCharge($charge_id);

        return $charge->refunds->create([
            'amount' => $amount,
            'description' => $desc,
        ]);
    }

    public function getRefund($charge_id, $refund_id = null)
    {
        $charge = $this->pingxxPayment->getCharge($charge_id);

        if (!is_null($refund_id)) {
            if($refund_id instanceof  Refund) {
                return $refund_id;
            }
            return $charge->refunds->retrieve($refund_id);
        }

        return $charge->refunds->all(['limit' => 1]);
    }

    public function getRefundTransaction($refund)
    {
        return $refund->transaction_no;
    }


    public function createRefundPayment($payment, $amount, $billing_id)
    {
        $refer_payment = $this->pingxxPayment->getPayment($payment);

        $refund = $this->createRefund($refer_payment['charge_id'], $amount);

        $refund_payment = new PingxxPaymentRefund([
            'pingxx_payment_id' => $refer_payment['id'],
            'payment_no' => $refund->order_no,
            'refund_id' => $refund->id,
            'charge_id' => $refund->charge,
            'succeed' => $refund->succeed,
            'amount' => $refund->amount,
            'status' => $refund->status,
            'billing_id' => $billing_id,
            'billing_type' => $refer_payment['billing_type']
        ]);

        if (PingxxProtocol::isSucceed($refund)) {
            $refund_payment->fill([
                'transaction_no' => $refund->transaction_no,
                'time_succeed' => $refund->time_succeed,
            ]);
            $refund_payment->save();

            event(new PingxxRefundPaymentIsSucceed($refund_payment));
            
        } else {
            $refund_payment->fill([
                'failure_code' => $refund->failure_code,
                'failure_msg' => $refund->failure_msg,
            ]);
            $refund_payment->save();

            event(new PingxxRefundPaymentIsFail($refund_payment));
        }

        return $refund_payment;
    }

    public function getRefundPayment($payment_no)
    {
        if ($payment_no instanceof PingxxPaymentRefund) {
            return $payment_no;
        }

        return PingxxPaymentRefund::query()->where('payment_no', $payment_no)->firstOrFail();
    }


    public function getRefundPaymentByBilling($billing_id, $billing_type)
    {
        return PingxxPaymentRefund::query()->where('billing_id', $billing_id)->where('billing_type', $billing_type)->first();
    }

    public function getRefundPaymentsByPayment($payment_id)
    {
        return PingxxPaymentRefund::query()->where('pingxx_payment_id', $payment_id)->get();
    }

    public function updateRefundPaymentAsDone($payment_no)
    {
        $payment = $this->getRefundPayment($payment_no);

        $payment->status = PingxxProtocol::STATUS_OF_REFUND_SUCCESS;
        $payment->succeed = true;
        $payment->save();

        event(new PingxxRefundPaymentIsDone($payment));

        return $payment;
    }

    public function updateRefundPaymentAsFail($payment_no, $failure_code, $failure_msg)
    {
        $payment = $this->getRefundPayment($payment_no);

        $payment->status = PingxxProtocol::STATUS_OF_REFUND_SUCCESS;
        $payment->succeed = false;
        $payment->failure_code = $failure_code;
        $payment->failure_msg = $failure_msg;
        $payment->save();

        event(new PingxxRefundPaymentIsFail($payment));

        return $payment;
    }
}

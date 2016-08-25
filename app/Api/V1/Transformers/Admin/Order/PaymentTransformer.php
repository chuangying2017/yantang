<?php namespace App\Api\V1\Transformers\Admin\Order;

use App\Models\Pay\PaymentAbstract;
use App\Models\Pay\PingxxPayment;
use League\Fractal\TransformerAbstract;

class PaymentTransformer extends TransformerAbstract {

    public function transform(PaymentAbstract $payment)
    {
        return [
            'id' => $payment['id'],
            'payment_no' => $payment['payment_no'],
            'billing_id' => $payment['billing_id'],
            'billing_type' => $payment['billing_type'],
            'amount' => $payment['amount'],
            'amount_settle' => $payment['amount_settle'],
            'charge_id' => $payment['charge_id'],
            'transaction_no' => $payment['transaction_no'],
            'channel' => $payment['channel'],
            'livemode' => $payment['livemode'],
            'currency' => $payment['currency'],
            'paid' => $payment['paid'],
            'refunded' => $payment['refunded'],
            'failure_code' => $payment['failure_code'],
            'failure_msg' => $payment['failure_msg'],
            'time_settle' => $payment['time_settle'],
            'time_expire' => $payment['time_expire'],
            'pay_at' => $payment['pay_at'],
        ];
    }

}

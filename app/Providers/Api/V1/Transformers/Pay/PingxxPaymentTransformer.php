<?php namespace App\Api\V1\Transformers\Pay;

use App\Models\Pay\PingxxPayment;
use League\Fractal\TransformerAbstract;

class PingxxPaymentTransformer extends TransformerAbstract {

    public function transform(PingxxPayment $payment)
    {
        return [
            'id' => $payment['id'],
            'payment_no' => $payment['payment_no'],
            'user' => ['id' => $payment['user_id']],
            'transaction_no' => $payment['transaction_no'],
            'amount' => $payment['amount'],
            'channel' => $payment['channel'],
            'status' => $payment['status'],
            'pay_at' => $payment['pay_at'],
            'refund' => [
                'id' => $payment['refund_id'],
                'amount' => $payment['amount_refunded']
            ],
            'failure' => [
                'code' => $payment['failure_code'],
                'msg' => $payment['failure_msg']
            ],
            'app' => $payment['app'],
        ];
    }
}

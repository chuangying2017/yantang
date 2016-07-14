<?php namespace App\Api\V1\Transformers\Mall;

use App\Api\V1\Transformers\Pay\PingxxPaymentTransformer;
use App\Models\Billing\OrderBilling;
use League\Fractal\TransformerAbstract;

class AdminOrderBillingTransformer extends TransformerAbstract {

    public function transform(OrderBilling $billing)
    {
        $this->defaultIncludes = ['payment'];

        $data = [
            'billing_no' => $billing['billing_no'],
            'order' => [
                'id' => $billing['order_id']
            ],
            'amount' => $billing['amount'],
            'pay_type' => $billing['pay_type'],
            'pay_channel' => $billing['pay_channel'],
            'status' => $billing['status'],
            'refund_flag' => false,
        ];
        if ($billing['refund_amount'] > 0) {
            $data['refund_flag'] = true;
            $data['refund_amount'] = $billing['refund_amount'];
        }

        return $data;
    }

    public function includePayment($billing)
    {
        if (!count($billing->payment)) {
            return null;
        }
        $payments = $billing->payment->filter(function ($payment, $key) {
            return $payment['paid'];
        });

        if(!count($payments)) {
            return null;
        }

        return $this->item($payments->first(), new PingxxPaymentTransformer(), true);
    }

}

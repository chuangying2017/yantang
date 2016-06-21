<?php namespace App\Api\V1\Transformers\Subscribe\Preorder;

use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\ChargeBilling;

class ChargeBillingTransformer extends TransformerAbstract
{

    public function transform(ChargeBilling $charge_billing)
    {
        $data = [
            'id' => $charge_billing->id,
            'user_id' => $charge_billing->user_id,
            'billing_no' => $charge_billing->billing_no,
            'pay_channel' => $charge_billing->pay_channel,
            'amount' => $charge_billing->amount / 10,
            'pay_at' => $charge_billing->pay_at,
            'status' => $charge_billing->status,
        ];

        return $data;
    }

}

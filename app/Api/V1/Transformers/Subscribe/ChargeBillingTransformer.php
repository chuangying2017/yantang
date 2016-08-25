<?php namespace App\Api\V1\Transformers\Subscribe;

use App\Models\Billing\ChargeBilling;
use League\Fractal\TransformerAbstract;

class ChargeBillingTransformer extends TransformerAbstract {

    public function transform(ChargeBilling $billing)
    {
        return [
            'id' => $billing['id'],
            'billing_no' => $billing['billing_no'],
            'amount' => display_price($billing['amount']),
            'pay_channel' => $billing['pay_channel'],
            'pay_at' => $billing['pay_at'],
            'status' => $billing['status'],
        ];
    }

}

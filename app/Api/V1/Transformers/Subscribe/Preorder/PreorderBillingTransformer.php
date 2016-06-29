<?php namespace App\Api\V1\Transformers\Subscribe\Preorder;

use App\Models\Billing\PreorderBilling;
use League\Fractal\TransformerAbstract;


class PreorderBillingTransformer extends TransformerAbstract {

    public function transform(PreorderBilling $billing)
    {
        return [
            'id' => $billing->id,
            'user' => ['id' => $billing->user_id],
            'billing_no' => $billing->billing_no,
            'station' => ['id' => $billing->station_id],
            'staff' => ['id' => $billing->staff_id],
            'amount' => display_price($billing->amount),
            'pay_at' => $billing->pay_at,
            'status' => $billing->status,
            'checkout' => $billing->checkout,
        ];
    }

}

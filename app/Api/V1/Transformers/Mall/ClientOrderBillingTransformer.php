<?php namespace App\Api\V1\Transformers\Mall;

use App\Models\Billing\OrderBilling;
use League\Fractal\TransformerAbstract;

class ClientOrderBillingTransformer extends TransformerAbstract {

    public function transform(OrderBilling $billing)
    {
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
        if ($billing[$billing['refund_amount'] > 0]) {
            $data['refund_flag'] = true;
            $data['refund_amount'] = $billing['refund_amount'];
        }

        return $data;
    }

}

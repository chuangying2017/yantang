<?php namespace App\Repositories\Billing;

use App\Models\Billing\OrderBilling;
use App\Repositories\NoGenerator;
use App\Services\Billing\BillingProtocol;

class RefundOrderBillingRepository extends OrderBillingRepository {

    public function createBilling($amount, $entity_ids)
    {
        return OrderBilling::create([
            'billing_no' => NoGenerator::generateOrderBillingNo(),
            'refer_billing_id' => $entity_ids['refer_billing_id'],
            'order_id' => $entity_ids['order_id'],
            'amount' => $amount,
            'pay_type' => $this->getPayType(),
            'status' => BillingProtocol::STATUS_OF_UNPAID
        ]);
    }

}

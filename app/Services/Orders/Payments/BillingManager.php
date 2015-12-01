<?php namespace App\Services\Orders\Payments;

use App\Services\Orders\OrderProtocol;

class BillingManager {

    public static function mainBillingIsPaid($billing_id, $pingxx_payment_id)
    {
        try {
            $billing = BillingRepository::fetchBilling($billing_id);

            OrderProtocol::validStatus($billing['status'], OrderProtocol::STATUS_OF_PAID);

            BillingRepository::billingPaid($billing_id, $pingxx_payment_id);

        } catch (\Exception $e) {
            throw $e;
        }

    }

}

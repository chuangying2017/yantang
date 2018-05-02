<?php namespace App\Services\Billing;
interface RefundBillingContract extends BillingContract {

	/**
     * @return BillingContract
     */
    public function getReferBilling();
}

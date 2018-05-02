<?php namespace App\Services\Billing;

/**
 * Interface BillingContract
 * @package App\Services\Billing
 */
interface BillingContract {

    public function getID();

    public function getOrderNo();

    public function isPaid($check = false);

    public function getAmount();

    public function getType();

    public function getPayer();

	/**
     * @param $billing_id
     * @return $this
     */
    public function setID($billing_id);

    public function getRefundedAmount();

    public function getPayment();


}

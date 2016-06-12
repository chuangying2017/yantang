<?php namespace App\Services\Billing;

/**
 * Interface BillingContract
 * @package App\Services\Billing
 */
interface BillingContract {

//    public function create($amount);

    public function getID();

    public function getOrderNo();

    public function isPaid();

    public function getAmount();

    public function getType();

    public function getPayer();

    public function setID($billing_id);

//    public function setPaid($pay_type, $pay_channel);

}

<?php namespace App\Services\Billing;

/**
 * Interface BillingContract
 * @package App\Services\Billing
 */
interface BillingContract
{

    public function getID();

    public function getOrderNo();

    public function isPaid();

    public function getAmount();

    public function getType();

    public function getPayer();

    public function setID($billing_id);

}

<?php namespace App\Services\Billing;

/**
 * Interface BillingContract
 * @package App\Services\Billing
 */
interface BillingContract {

    public function create($amount);

    public function getID();

    public function getAmount();

    public function getType();

    public function getPayer();

}

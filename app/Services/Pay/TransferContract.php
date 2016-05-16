<?php namespace App\Services\Pay;

use App\Services\Billing\BillingContract;

interface TransferContract {

    public function transfer(BillingContract $billingContract);

}

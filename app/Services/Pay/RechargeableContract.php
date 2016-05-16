<?php namespace App\Services\Pay;

use App\Services\Billing\BillingContract;

interface RechargeableContract {

    public function recharge(BillingContract $billing);

    public function validRecharge(BillingContract $billing);

}

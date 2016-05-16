<?php namespace App\Services\Pay;

use App\Services\Billing\BillingContract;

interface PayableContract {

    public function pay(BillingContract $billing);

    public function enough($need_amount);

}

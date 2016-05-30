<?php namespace App\Services\Client\Account;

use App\Repositories\Client\Account\Credits\CreditsRepositoryContract;
use App\Services\Billing\BillingContract;

class CreditsService extends AccountService {

    public function __construct(CreditsRepositoryContract $account)
    {
        parent::__construct($account);
    }

    public function transAmount($billing_amount)
    {
        // TODO: Implement getAmount() method.
    }

    public function validRecharge(BillingContract $billing)
    {
        // TODO: Implement validRecharge() method.
    }
}

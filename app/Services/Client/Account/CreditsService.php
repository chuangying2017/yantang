<?php namespace App\Services\Client\Account;

use App\Repositories\Client\Account\Credits\CreditsRepositoryContract;
use App\Services\Billing\BillingContract;
use App\Services\Billing\BillingProtocol;
use App\Services\Pay\Exception\MultiChargeException;
use App\Services\Pay\Exception\WrongChargeBillingException;

class CreditsService extends AccountService {

    public function __construct(CreditsRepositoryContract $account)
    {
        parent::__construct($account);
    }

    public function transAmount($billing_amount)
    {
        return $billing_amount;
    }

    public function validRecharge(BillingContract $billing)
    {
        if ($billing->getType() !== BillingProtocol::BILLING_TYPE_OF_ORDER_PROMOTION) {
            throw new WrongChargeBillingException();
        }

        if ($this->account->setUserId($billing->getPayer())->getRecord($billing->getID(), $billing->getType())) {
            throw new MultiChargeException();
        }
    }
}

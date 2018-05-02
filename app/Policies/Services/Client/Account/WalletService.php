<?php namespace App\Services\Client\Account;

use App\Repositories\Client\Account\Wallet\WalletRepositoryContract;
use App\Services\Billing\BillingContract;
use App\Services\Billing\BillingProtocol;
use App\Services\Pay\Exception\MultiChargeException;
use App\Services\Pay\Exception\WrongChargeBillingException;

class WalletService extends AccountService {
    
	/**
     * WalletService constructor.
     * @param WalletRepositoryContract $account
     */
    public function __construct(WalletRepositoryContract $account)
    {
        parent::__construct($account);
    }


	/**
     * @param BillingContract $billing
     * @throws MultiChargeException
     * @throws WrongChargeBillingException
     */
    public function validRecharge(BillingContract $billing)
    {
        if ($billing->getType() !== BillingProtocol::BILLING_TYPE_OF_RECHARGE_BILLING) {
            throw new WrongChargeBillingException();
        }

        if ($this->account->setUserId($billing->getPayer())->getRecord($billing->getID(), $billing->getType())) {
            throw new MultiChargeException();
        }
    }

    /**
     * 需要支付金额与帐号类型金额单位换算
     * @param $billing_amount
     * @return int
     */
    public function transAmount($billing_amount)
    {
        return $billing_amount;
    }
}

<?php namespace App\Services\Client\Account;

use App\Repositories\Client\Account\AccountRepositoryContract;
use App\Services\Billing\BillingContract;
use App\Services\Pay\Exception\NotEnoughException;
use App\Services\Pay\PayableContract;
use App\Services\Pay\RechargeableContract;
use App\Services\Pay\TransferContract;

abstract class AccountService implements PayableContract, RechargeableContract, TransferContract {

    protected $account;

    protected $user_id = null;

    /**
     * WalletService constructor.
     * @param AccountRepositoryContract $account
     */
    public function __construct(AccountRepositoryContract $account)
    {
        $this->account = $account;
    }

    /**
     * @param $need_amount
     * @return bool
     */
    public function enough($need_amount)
    {
        $account_amount = $this->account->getAmount();
        return $account_amount >= $this->transAmount($need_amount);
    }


    public abstract function transAmount($billing_amount);

    public function pay(BillingContract $billing)
    {
        $pay_amount = $this->transAmount($billing->getAmount());

        if (!$this->enough($pay_amount)) {
            throw new NotEnoughException();
        }

        $record = $this->account->change(
            $pay_amount,
            $billing->getType(),
            $billing->getID(),
            AccountProtocol::ACCOUNT_AMOUNT_USED_NAME,
            AccountProtocol::ACCOUNT_AMOUNT_MAIN_NAME
        );

        return $record;
    }


    public abstract function validRecharge(BillingContract $billing);

    public function recharge(BillingContract $billing)
    {
        $this->validRecharge($billing);

        $pay_amount = $this->transAmount($billing->getAmount());

        $record = $this->account->change(
            $pay_amount,
            $billing->getType(),
            $billing->getID(),
            AccountProtocol::ACCOUNT_AMOUNT_MAIN_NAME,
            null
        );

        return $record;
    }

    public function transfer(BillingContract $billingContract)
    {
        // TODO: Implement transfer() method.
    }

    /**
     * @param null $user_id
     * @return WalletService
     */
    public function setPayer($user_id)
    {
        $this->user_id = $user_id;
        $this->getAccount()->setUserId($user_id);
        return $this;
    }

    /**
     * @return null
     */
    public function getPayer()
    {
        return $this->user_id;
    }

    /**
     * @return AccountRepositoryContract
     */
    public function getAccount()
    {
        return $this->account;
    }

}

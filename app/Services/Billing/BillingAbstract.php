<?php namespace App\Services\Billing;

use App\Services\Order\OrderProtocol;

abstract class BillingAbstract implements BillingContract {

    protected $billing;

    public function getID()
    {
        return $this->billing['id'];
    }

    public function isPaid($check = false)
    {
        return $this->billing['status'] == OrderProtocol::PAID_STATUS_OF_PAID;
    }

    public function getAmount()
    {
        return $this->billing['amount'];
    }

    public function getPayer()
    {
        return $this->billing['user_id'];
    }

    public function getPayType()
    {
        return $this->billing['pay_type'];
    }

    public function getRefundedAmount()
    {
        return $this->billing['return_amount'];
    }

    public function getPayment()
    {
        return $this->billing->payment()->first();
    }

}

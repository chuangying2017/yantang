<?php namespace App\Services\Billing;

use App\Repositories\Billing\ChargeBillingRepository;

class ChargeBillingService extends BillingAbstract {

    /**
     * OrderBilling constructor.
     * @param ChargeBillingRepository $orderBillingRepo
     */
    public function __construct(ChargeBillingRepository $orderBillingRepo)
    {
        $this->orderBillingRepo = $orderBillingRepo;
    }

    public function getOrderNo()
    {
        return $this->billing['billing_no'];
    }

    public function getType()
    {
        return BillingProtocol::BILLING_TYPE_OF_RECHARGE_BILLING;
    }

    public function setID($billing_id)
    {
        $this->billing = $this->orderBillingRepo->getBilling($billing_id);
        return $this;
    }
}

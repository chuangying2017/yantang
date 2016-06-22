<?php namespace App\Services\Billing;

use App\Repositories\Billing\OrderBillingRepository;

class OrderBillingService extends BillingAbstract {

    /**
     * @var OrderBillingRepository
     */
    private $orderBillingRepo;

    /**
     * OrderBilling constructor.
     * @param OrderBillingRepository $orderBillingRepo
     */
    public function __construct(OrderBillingRepository $orderBillingRepo)
    {
        $this->orderBillingRepo = $orderBillingRepo;
    }


    public function getOrderNo()
    {
        return $this->billing['billing_no'];
    }

    public function getType()
    {
        return BillingProtocol::BILLING_TYPE_OF_ORDER_BILLING;
    }

    public function setID($billing_id)
    {
        $this->billing = $this->orderBillingRepo->getBilling($billing_id);
        return $this;
    }

}

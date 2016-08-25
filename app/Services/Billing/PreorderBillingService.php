<?php namespace App\Services\Billing;

use App\Models\Billing\PreorderBilling;
use App\Repositories\Billing\PreorderBillingRepository;
use App\Services\Subscribe\SubscribeProtocol;

class PreorderBillingService extends  BillingAbstract
{

    /**
     * @var PreorderBillingRepository
     */
    private $preorderBillingRepo;

    /**
     * PreorderBillingService constructor.
     * @param PreorderBillingRepository $preorderBillingRepo
     */
    public function __construct(PreorderBillingRepository $preorderBillingRepo)
    {
        $this->preorderBillingRepo = $preorderBillingRepo;
    }

    public function getOrderNo()
    {
        return $this->billing['billing_no'];
    }

    public function getType()
    {
        return BillingProtocol::BILLING_TYPE_OF_PREORDER_ORDER_BILLING;
    }

    public function setID($billing_id)
    {
        $this->billing = $this->preorderBillingRepo->getBilling($billing_id);
        return $this;
    }
}

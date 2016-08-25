<?php namespace App\Services\Billing;

use App\Repositories\Billing\OrderBillingRepository;
use App\Services\Order\OrderProtocol;
use App\Services\Pay\Pingxx\PingxxPayService;

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

    public function getPayType()
    {
        return $this->billing['pay_type'];
    }

    public function isPaid($check_third_party = false)
    {
        $paid = $this->billing['status'] == OrderProtocol::PAID_STATUS_OF_PAID;

        if (!$paid && $check_third_party) {
            return app()->make(PingxxPayService::class)->checkBillingIsPaid($this->getID(), $this->getPayType());
        }

        return $paid;
    }

}

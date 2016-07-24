<?php namespace App\Services\Billing;

use App\Models\Billing\OrderBilling;
use App\Repositories\Billing\RefundOrderBillingRepository;

class RefundOrderBillingService extends OrderBillingService implements RefundBillingContract {

    public function __construct(RefundOrderBillingRepository $orderBillingRepo)
    {
        parent::__construct($orderBillingRepo);
    }

	/**
     * @return OrderBilling
     */
    public function getReferBilling()
    {
        return $this->billing->refer;
    }
    
    
}

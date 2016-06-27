<?php namespace App\Repositories\Billing;
use App\Services\Billing\BillingProtocol;

interface BillingRepositoryContract {

    public function createBilling($amount, $entity_id);

    public function updateAsPaid($billing_no, $pay_channel = null);

    public function getBilling($billing_no);

    public function getAllBilling($entity_id, $status = null);

    public function getBillingPaginated($entity_id, $status = null, $per_page = BillingProtocol::BILLING_PER_PAGE);

    public function getBillingOfType($order_id, $pay_type);


}

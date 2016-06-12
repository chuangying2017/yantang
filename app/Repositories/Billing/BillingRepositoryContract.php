<?php namespace App\Repositories\Billing;
interface BillingRepositoryContract {

    public function createBilling($amount, $entity_id);

    public function updateAsPaid($billing_no, $pay_channel);

    public function getBilling($billing_no);

    public function getAllBilling($entity_id, $status = null);

    public function getBillingOfType($order_id, $pay_type);


}

<?php namespace App\Repositories\Statement;
interface StatementAbleBillingRepoContract {

    public function getBillingWithProducts($merchant_id, $time_before);

    public function updateBillingAsCheckout($billing_ids);

}

<?php namespace App\Services\Store\Statement;
interface StoreStatementServiceContract {

    public function generateStatements();

    public function generateStoreStatement($store_id);

    public function reconciliation($store_id, $statement_no, $confirm = true, $memo = null);

}

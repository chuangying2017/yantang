<?php namespace App\Repositories\Store\Statement;

interface StoreStatementRepositoryContract {

    public function getAllStatements($year, $month, $status = null);

    public function getAllStatementsOfStore($store_id, $year, $status = null);

    public function createStatement($store_id, $settle_amount, $service_amount, $product_skus_info);

    public function getStatementByTime($year, $month, $store_id);

    public function updateStatementAsOK($statement_no);

    public function updateStatementAsError($statement_no, $memo = '');

    public function getStatement($statement_no, $with_detail = false);

    public function getStatementDetail($statement_no);

}

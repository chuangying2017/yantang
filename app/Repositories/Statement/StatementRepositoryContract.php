<?php namespace App\Repositories\Statement;

interface StatementRepositoryContract {

    public function getAllStatements($year, $month, $status = null);

    public function getAllStatementsOfMerchant($merchant_id, $year, $status = null);

    public function createStatement($merchant_id, $settle_amount, $service_amount, $product_skus_info);

    public function getStatementByTime($year, $month, $merchant_id);

    public function updateStatementAsOK($statement_no);

    public function updateStatementAsError($statement_no, $memo = '');

    public function getStatement($statement_no, $with_detail = false);

    public function getStatementDetail($statement_no);

}

<?php namespace App\Services\Store\Statement;
interface StoreStatementServiceContract {

    //生成对账单
    public function generateStatements();

    //对账
    public function reconciliation($store_id, $statement_no, $confirm = true, $memo = null);

}

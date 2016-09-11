<?php namespace App\Repositories\Invoice;
interface InvoiceRepositoryContract {

    public function create($invoice_data);

    public function get($invoice_no, $with_detail = false);

    public function getPaginatedOfMerchant($merchant_id, $start_date = null, $end_date = null, $status = null);

    public function getAllOfMerchant($merchant_id, $start_date = null, $end_date = null, $status = null);

    public function getAllByAdmin($invoice_date);

    public function updateAsOk($invoice_no);

    public function updateAsError($invoice_no, $memo = '');

}

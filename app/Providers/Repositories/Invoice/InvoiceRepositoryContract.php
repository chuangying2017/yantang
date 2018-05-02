<?php namespace App\Repositories\Invoice;
interface InvoiceRepositoryContract {

    public function create($invoice_data);

    public function get($invoice_no, $with_detail = false);

    public function getAllPaginated($merchant_id, $start_date = null, $end_date = null, $status = null);

    public function getAll($merchant_id, $start_date = null, $end_date = null, $status = null);

    public function updateAsOk($invoice_no);

    public function updateAsError($invoice_no, $memo = '');
    
    public function updateAsReconfirm($invoice_no);

    public function getAllOrders($invoice_no, $per_page = null, $staff_id = null);

}

<?php namespace App\Services\Invoice;
interface InvoiceServiceContract {

    public function settleAll($invoice_date);

    public function settleMerchant($merchant_id, $request_invoice_date, $start_time, $end_time);

}

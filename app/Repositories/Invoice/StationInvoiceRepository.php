<?php namespace App\Repositories\Invoice;


use App\Models\Invoice\StationInvoice;
use App\Repositories\NoGenerator;

class StationInvoiceRepository extends InvoiceRepositoryAbstract {

    protected function init()
    {
        $this->setInvoiceModel(InvoiceProtocol::INVOICE_MODEL_OF_STATION)->setInvoiceType(InvoiceProtocol::INVOICE_TYPE_OF_STATION);
    }
    
}

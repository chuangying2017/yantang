<?php namespace App\Repositories\Invoice;

use App\Models\Invoice\StationInvoice;

class StationAdminInvoiceRepository extends InvoiceRepositoryAbstract {

    protected function init()
    {
        $this->setInvoiceModel(InvoiceProtocol::INVOICE_MODEL_OF_STATION_ADMIN)->setInvoiceType(InvoiceProtocol::INVOICE_TYPE_OF_STATION_ADMIN);
    }

    public function get($invoice_no, $with_detail = false)
    {
        if ($invoice_no instanceof $this->invoice_model) {
            $invoice = $invoice_no;
        } else {
            $invoice = $this->getInvoiceModelQuery()->where('invoice_no', $invoice_no)->firstOrFail();
        }

        if ($with_detail) {
            if($invoice['merchant_id'] == InvoiceProtocol::ID_OF_ADMIN_INVOICE) {
                $invoice->detail = StationInvoice::query()
                    ->where('merchant_id', '!=', InvoiceProtocol::ID_OF_ADMIN_INVOICE)
                    ->where('invoice_date', $invoice['invoice_date'])
                    ->get();
            }
        }

        return $invoice;
    }
}

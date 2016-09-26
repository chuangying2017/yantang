<?php namespace App\Repositories\Invoice;

use App\Models\Invoice\StationInvoice;
use App\Repositories\NoGenerator;

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
            if ($invoice['merchant_id'] == InvoiceProtocol::ID_OF_ADMIN_INVOICE) {
                $invoice->detail = StationInvoice::query()
                    ->whereNotIn('merchant_id', InvoiceProtocol::ID_OF_ADMIN_INVOICE)
                    ->where('invoice_date', $invoice['invoice_date'])
                    ->get();
            }
        }

        return $invoice;
    }


    public function create($invoice_data)
    {
        $invoice_orders = array_get($invoice_data, 'detail', null);

        $invoice_model = $this->getInvoiceModel();

        $invoice_no = NoGenerator::generateInvoiceNo($this->getInvoiceModel(), $invoice_data['invoice_date'], $invoice_data['merchant_id']);

        $invoice = $invoice_model::query()->updateOrcreate(['invoice_no' => $invoice_no], [
            'invoice_no' => $invoice_no,
            'invoice_date' => $invoice_data['invoice_date'],
            'start_time' => $invoice_data['start_time'],
            'end_time' => $invoice_data['end_time'],
            'merchant_id' => $invoice_data['merchant_id'],
            'merchant_name' => $invoice_data['merchant_name'],
            'total_count' => array_get($invoice_data, 'total_count', count($invoice_orders)),
            'total_amount' => $invoice_data['total_amount'],
            'discount_amount' => $invoice_data['discount_amount'],
            'pay_amount' => $invoice_data['pay_amount'],
            'service_amount' => $invoice_data['service_amount'],
            'receive_amount' => $invoice_data['receive_amount'],
            'status' => InvoiceProtocol::INVOICE_STATUS_OF_PENDING,
            'memo' => '',
        ]);

        return $invoice;
    }
}

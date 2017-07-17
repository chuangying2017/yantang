<?php namespace App\Repositories\Invoice;
use App\Models\Invoice\StationUnInvoice;
use App\Models\Subscribe\Preorder;

use App\Repositories\NoGenerator;

class StationRefundInvoiceRepository extends InvoiceRepositoryAbstract{

    protected function init()
    {
        $this->setInvoiceModel(InvoiceProtocol::INVOICE_MODEL_OF_STATION_REFUND)->setInvoiceType(InvoiceProtocol::INVOICE_TYPE_OF_STATION_REFUND);
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

        $invoice->orders()->createMany($invoice_orders);

        $invoice_preorder_ids = array_pluck($invoice_orders, 'preorder_id');
        \Log::info('invoice_preorder_ids', [$invoice_preorder_ids]);
        Preorder::query()->whereIn('id', $invoice_preorder_ids)->update(['invoice' => InvoiceProtocol::PREORDER_INVOICE_ORDER_OF_OK]);


        return $invoice;
    }
}

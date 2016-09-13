<?php namespace App\Api\V1\Transformers\Invoice;

use App\Api\V1\Transformers\Traits\SetInclude;
use App\Models\Invoice\StationInvoice;
use League\Fractal\TransformerAbstract;

class StationInvoiceTransformer extends TransformerAbstract {

    use SetInclude;

    protected $availableIncludes = ['orders'];

    public function transform(StationInvoice $invoice)
    {
        $this->setInclude($invoice);

        return [
            'invoice_no' => $invoice['invoice_no'],
            'invoice_date' => $invoice['invoice_date'],
            'start_time' => $invoice['start_time'],
            'end_time' => $invoice['end_time'],
            'merchant_id' => $invoice['merchant_id'],
            'merchant_name' => $invoice['merchant_name'],
            'status' => $invoice['status'],
            'total_count' => $invoice['total_count'],
            'total_amount' => display_price($invoice['total_amount']),
            'discount_amount' => display_price($invoice['discount_amount']),
            'pay_amount' => display_price($invoice['pay_amount']),
            'service_amount' => display_price($invoice['service_amount']),
            'receive_amount' => display_price($invoice['receive_amount']),
        ];
    }

    public function includeOrders(StationInvoice $invoice)
    {
        return $this->collection($invoice->orders, new StationInvoiceOrderTransformer(), true);
    }

}

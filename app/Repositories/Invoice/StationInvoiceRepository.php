<?php namespace App\Repositories\Invoice;


use App\Models\Invoice\StationInvoice;
use App\Repositories\NoGenerator;

class StationInvoiceRepository implements InvoiceRepositoryContract {


    public function create($invoice_data)
    {

        $invoice_orders = $invoice_data['detail'];

        \DB::beginTransaction();

        $invoice = StationInvoice::create([
            'invoice_no' => NoGenerator::generateStationInvoiceNo($invoice_data['invoice_date'], $invoice_data['merchant_id']),
            'invoice_date' => $invoice_data['invoice_date'],
            'start_time' => $invoice_data['start_time'],
            'end_time' => $invoice_data['end_time'],
            'merchant_id' => $invoice_data['merchant_id'],
            'merchant_name' => $invoice_data['merchant_name'],
            'total_count' => count($invoice_orders),
            'total_amount' => $invoice_data['total_amount'],
            'discount_amount' => $invoice_data['discount_amount'],
            'pay_amount' => $invoice_data['pay_amount'],
            'service_amount' => $invoice_data['service_amount'],
            'receive_amount' => $invoice_data['receive_amount'],
            'type' => InvoiceProtocol::INVOICE_TYPE_OF_STATION,
            'status' => InvoiceProtocol::INVOICE_STATUS_OF_PENDING,
            'memo' => '',
        ]);

        $invoice->orders()->createMany($invoice_orders);

        \DB::commit();

        return $invoice;
    }

    public function get($invoice_no, $with_detail = false)
    {
        if ($invoice_no instanceof StationInvoice) {
            $invoice = $invoice_no;
        } else {
            $invoice = StationInvoice::query()->where('invoice_no', $invoice_no)->firstOrFail();
        }

        if ($with_detail) {
            $invoice->load('orders');
        }

        return $invoice;
    }

    public function getPaginatedOfMerchant($merchant_id, $start_date = null, $end_date = null, $status = null)
    {
        return $this->query($merchant_id, $start_date, $end_date, $status);
    }

    /**
     * @param $merchant_id
     * @param null $start_date
     * @param null $end_date
     * @param null $status
     */
    public function getAllOfMerchant($merchant_id, $start_date = null, $end_date = null, $status = null)
    {
        return $this->query($merchant_id, $start_date, $end_date, $status, null);
    }

    public function getAllByAdmin($invoice_date)
    {
        return $this->query(null, $invoice_date, $invoice_date, null, null);
    }

    public function updateAsOk($invoice_no)
    {
        return $this->updateStatus($invoice_no, InvoiceProtocol::INVOICE_STATUS_OF_CONFIRM);
    }

    public function updateAsError($invoice_no, $memo = '')
    {
        return $this->updateStatus($invoice_no, InvoiceProtocol::INVOICE_STATUS_OF_ERROR, $memo);
    }

    protected function updateStatus($invoice_no, $status, $memo = '')
    {
        $invoice = $this->get($invoice_no, false);

        if ($invoice !== InvoiceProtocol::INVOICE_STATUS_OF_PENDING) {
            return $invoice;
        }

        $invoice->status = $status;
        $invoice->memo = $memo;
        $invoice->save();

        return $invoice;
    }

    protected function query($merchant_id = null, $start_date = null, $end_date = null, $status = null, $paginate = InvoiceProtocol::PER_PAGE)
    {
        $query = StationInvoice::query();

        if (!is_null($merchant_id)) {
            if (is_array($merchant_id)) {
                $query->whereIn('merchant_id', $merchant_id);
            } else {
                $query->where('merchant_id', $merchant_id);
            }
        }

        if (!is_null($start_date)) {
            $query->where('invoice_date', '>=', $start_date);
        }

        if (!is_null($end_date)) {
            $query->where('invoice_date', '<=', $end_date);
        }

        if (!is_null($status)) {
            $query->where('status', $status);
        }

        if (!is_null($paginate)) {
            return $query->paginate($paginate);
        }

        return $query->get();
    }
}

<?php namespace App\Repositories\Invoice;

use App\Models\Invoice\InvoiceAbstract;
use App\Models\Subscribe\Preorder;
use App\Repositories\NoGenerator;

abstract class InvoiceRepositoryAbstract implements InvoiceRepositoryContract {


    /** @var InvoiceAbstract */
    protected $invoice_model = null;

    protected $invoice_type = null;

    // set model,type
    protected abstract function init();

    public function __construct()
    {
        $this->init();
    }

    public function create($invoice_data)
    {
        \DB::beginTransaction();


        $invoice_orders = array_get($invoice_data, 'detail', null);

        $invoice_model = $this->getInvoiceModel();


        $invoice = $invoice_model::create([
            'invoice_no' => NoGenerator::generateInvoiceNo($this->getInvoiceModel(), $invoice_data['invoice_date'], $invoice_data['merchant_id']),
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

        if (InvoiceProtocol::invoiceHasOrders($this->getInvoiceType())) {
            $invoice->orders()->createMany($invoice_orders);
            $invoice_preorder_ids =  array_pluck($invoice_orders, 'preorder_id');
            Preorder::query()->whereIn('id', $invoice_preorder_ids)->update(['invoice' => 1]);
        }

        \DB::commit();

        return $invoice;
    }

    public function get($invoice_no, $with_detail = false)
    {
        if ($invoice_no instanceof $this->invoice_model) {
            $invoice = $invoice_no;
        } else {
            $invoice = $this->getInvoiceModelQuery()->where('invoice_no', $invoice_no)->firstOrFail();
        }

        if ($with_detail) {
            $invoice->load(['orders']);
        }

        return $invoice;
    }

    public function getAllPaginated($merchant_id, $start_date = null, $end_date = null, $status = null)
    {
        return $this->query($merchant_id, $start_date, $end_date, $status);
    }

    /**
     * @param $merchant_id
     * @param null $start_date
     * @param null $end_date
     * @param null $status
     */
    public function getAll($merchant_id, $start_date = null, $end_date = null, $status = null)
    {
        return $this->query($merchant_id, $start_date, $end_date, $status, null);
    }

    public function updateAsOk($invoice_no)
    {
        return $this->updateStatus($invoice_no, InvoiceProtocol::INVOICE_STATUS_OF_CONFIRM);
    }

    public function updateAsError($invoice_no, $memo = '')
    {
        return $this->updateStatus($invoice_no, InvoiceProtocol::INVOICE_STATUS_OF_REJECT, $memo);
    }

    protected function updateStatus($invoice_no, $status, $memo = '')
    {
        $invoice = $this->get($invoice_no, false);

        if ($invoice['status'] !== InvoiceProtocol::INVOICE_STATUS_OF_PENDING) {
            return $invoice;
        }

        $invoice->status = $status;
        $invoice->memo = $memo;
        $invoice->save();

        return $invoice;
    }

    protected function query($merchant_id = null, $start_date = null, $end_date = null, $status = null, $paginate = InvoiceProtocol::PER_PAGE)
    {
        $query = $this->getInvoiceModelQuery();


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

    /**
     * @return InvoiceAbstract
     */
    protected function getInvoiceModel()
    {
        if (is_null($this->invoice_model)) {
            throw new \Exception('初始化 invoice model 失败');
        }
        return $this->invoice_model;
    }

    /**
     * @param InvoiceAbstract $invoice_model
     * @return $this
     */
    public function setInvoiceModel($invoice_model)
    {
        $this->invoice_model = $invoice_model;
        return $this;
    }

    public function getInvoiceModelQuery()
    {
        if (is_null($this->invoice_model)) {
            throw new \Exception('初始化 invoice model 失败');
        }

        $model = $this->invoice_model;
        return $model::query();
    }

    /**
     * @return mixed
     */
    public function getInvoiceType()
    {
        if (is_null($this->invoice_type)) {
            throw new \Exception('初始化 invoice type 失败');
        }
        return $this->invoice_type;
    }

    /**
     * @param mixed $invoice_type
     * @return $this
     */
    public function setInvoiceType($invoice_type)
    {
        $this->invoice_type = $invoice_type;
        return $this;
    }


    public function getAllOrders($invoice_no, $per_page = null)
    {
        $order_model = InvoiceProtocol::getOrderModel($this->getInvoiceModel());

        $query = $order_model::query()->where('invoice_no', $invoice_no);

        if ($per_page) {
            return $query->paginate($per_page);
        }

        return $query->get();
    }

}

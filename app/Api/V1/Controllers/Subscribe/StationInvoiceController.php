<?php

namespace App\Api\V1\Controllers\Subscribe;

use App\Api\V1\Transformers\Invoice\StationInvoiceOrderTransformer;
use App\Api\V1\Transformers\Invoice\StationInvoiceTransformer;
use App\Api\V1\Transformers\Invoice\StationInvoiceCollectOrderTransformer;
use App\Repositories\Backend\AccessProtocol;
use App\Repositories\Invoice\InvoiceProtocol;
use App\Repositories\Invoice\StationInvoiceRepository;
use App\Services\Chart\ExcelService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Api\V1\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class StationInvoiceController extends Controller {

    /**
     * @var StationInvoiceRepository
     */
    private $invoiceRepo;

    /**
     * StationInvoiceController constructor.
     * @param StationInvoiceRepository $invoiceRepo
     */
    public function __construct(StationInvoiceRepository $invoiceRepo)
    {
        $this->invoiceRepo = $invoiceRepo;
    }

    protected function checkAuth($invoice)
    {
        if (access()->hasRole(AccessProtocol::ROLE_OF_SUPERVISOR)) {
            return true;
        }

        if ($invoice['merchant_id'] != access()->stationId()) {
            throw new AccessDeniedHttpException('无权查看账单');
        }
    }

    public function index(Request $request)
    {
        $start_time = $request->input('start_time') ?: null;
        $end_time = $request->input('end_time') ?: null;
        $status = $request->input('status') ?: null;

        $invoices = $this->invoiceRepo->getAllPaginated(access()->stationId(), $start_time, $end_time, $status);

        return $this->response->paginator($invoices, new StationInvoiceTransformer());
    }

    public function show(Request $request, $invoice_no)
    {
        $invoice = $this->invoiceRepo->get($invoice_no, false);

        $this->checkAuth($invoice);


        if ($request->input('export') == 'all') {
            return ExcelService::downloadStationInvoice($invoice);
        }

        return $this->response->item($invoice, new StationInvoiceTransformer());
    }

    public function update(Request $request, $invoice_no)
    {
        $action = $request->input('action');
        $memo = $request->input('memo');

        $invoice = $this->invoiceRepo->get($invoice_no, false);

        $this->checkAuth($invoice);

        if ($action == InvoiceProtocol::INVOICE_STATUS_OF_CONFIRM) {
            $invoice = $this->invoiceRepo->updateAsOk($invoice);
        } else if ($action == InvoiceProtocol::INVOICE_STATUS_OF_RECONFIRM) {
            $invoice = $this->invoiceRepo->updateAsReconfirm($invoice);
        } else {
            $invoice = $this->invoiceRepo->updateAsError($invoice, $memo);
        }

        return $this->response->item($invoice, new StationInvoiceTransformer());
    }

    public function orders(Request $request, $invoice_no)
    {
        $invoice = $this->invoiceRepo->get($invoice_no, false);

        $this->checkAuth($invoice);

        $staff_id = $request->input('staff') ?: null;

        $orders = $this->invoiceRepo->getAllOrders($invoice_no, InvoiceProtocol::PER_PAGE_OF_ORDER, $staff_id);

        return $this->response->paginator($orders, new StationInvoiceOrderTransformer());
    }


    public function collect_orders(Request $request, $invoice_no)
    {
        $invoice = $this->invoiceRepo->get($invoice_no, false);

        $this->checkAuth($invoice);

        $staff_id = $request->input('staff') ?: null;

        $orders = $this->invoiceRepo->getAllOrders($invoice_no, InvoiceProtocol::PER_PAGE_OF_ORDER, $staff_id, 'collect');

        return $this->response->paginator($orders, new StationInvoiceCollectOrderTransformer());
    }

}

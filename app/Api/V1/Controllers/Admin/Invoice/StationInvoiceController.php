<?php

namespace App\Api\V1\Controllers\Admin\Invoice;

use App\API\V1\Transformers\Admin\Invoice\StationInvoiceTransformer;
use App\Repositories\Invoice\InvoiceProtocol;
use App\Repositories\Invoice\StationAdminInvoiceRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class StationInvoiceController extends Controller {

    /**
     * @var StationAdminInvoiceRepository
     */
    private $invoiceRepo;

    /**
     * StationInvoiceController constructor.
     * @param StationAdminInvoiceRepository $invoiceRepo
     */
    public function __construct(StationAdminInvoiceRepository $invoiceRepo)
    {
        $this->invoiceRepo = $invoiceRepo;
    }

    public function index(Request $request)
    {
        $start_time = $request->input('start_time') ?: null;
        $end_time = $request->input('end_time') ?: null;

        $invoices = $this->invoiceRepo->getAllPaginated(InvoiceProtocol::ID_OF_ADMIN_INVOICE, $start_time, $end_time);

        return $this->response->paginator($invoices, new StationInvoiceTransformer());
    }

    public function show($invoice_no)
    {
        $invoice = $this->invoiceRepo->get($invoice_no, true);
        
        return $this->response->item($invoice, new StationInvoiceTransformer());
    }

}


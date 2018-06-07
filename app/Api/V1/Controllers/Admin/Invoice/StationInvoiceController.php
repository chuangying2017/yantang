<?php

namespace App\Api\V1\Controllers\Admin\Invoice;

use App\Api\V1\Transformers\Admin\Invoice\StationInvoiceTransformer;
use App\Api\V1\Transformers\Admin\Invoice\StationInvoiceCollectOrderTransformer;
use App\Api\V1\Transformers\Invoice\StationInvoiceCollectOrderTransformer as Client_StationInvoiceCollectOrderTransformer;
use App\Api\V1\Transformers\Invoice\StationInvoiceOrderTransformer;
use App\Repositories\Backend\AccessProtocol;
use App\Repositories\Invoice\InvoiceProtocol;
use App\Repositories\Invoice\StationInvoiceRepository;
use App\Repositories\Invoice\StationAdminInvoiceRepository;
use App\Repositories\Preorder\PreorderRepositoryContract;
use App\Services\Chart\ExcelService;
use App\Services\Preorder\PreorderProtocol;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class StationInvoiceController extends Controller {

    /**
     * @var StationAdminInvoiceRepository
     */
    private $invoiceRepo;

    /**
     * @var StationInvoiceRepository
     */
    private $stationInvoiceRepo;

    /**
     * StationInvoiceController constructor.
     * @param StationAdminInvoiceRepository $invoiceRepo
     */
    public function __construct(StationAdminInvoiceRepository $invoiceRepo,StationInvoiceRepository $stationInvoiceRepo)
    {
        $this->invoiceRepo = $invoiceRepo;
        $this->stationInvoiceRepo = $stationInvoiceRepo;
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

        $invoices = $this->invoiceRepo->getAllPaginated(InvoiceProtocol::ID_OF_ADMIN_INVOICE, $start_time, $end_time);

        return $this->response->paginator($invoices, new StationInvoiceTransformer());
    }

    public function show(Request $request, $invoice_no)//export all station data information
    {
        $invoice = $this->invoiceRepo->get($invoice_no, true);
  
        if ($request->input('export') == 'summary') {
            return ExcelService::downloadStationAdminInvoice($invoice);
        } else if ($request->input('export') == 'all') {
            return ExcelService::downloadStationAdminInvoiceDetail($invoice);
        }

        return $this->response->item($invoice, new StationInvoiceTransformer());
    }

    public function orders(Request $request, $invoice_no)
    {
        $invoice = $this->stationInvoiceRepo->get($invoice_no, false);

        $this->checkAuth($invoice);

        $staff_id = $request->input('staff') ?: null;

        $orders = $this->stationInvoiceRepo->getAllOrders($invoice_no, InvoiceProtocol::PER_PAGE_OF_ORDER, $staff_id);

        return $this->response->paginator($orders, new StationInvoiceOrderTransformer());
    }


    public function collect_orders(Request $request, $invoice_no)
    {
        $invoice = $this->stationInvoiceRepo->get($invoice_no, false);

        $this->checkAuth($invoice);

        $staff_id = $request->input('staff') ?: null;

        $orders = $this->stationInvoiceRepo->getAllOrders($invoice_no, InvoiceProtocol::PER_PAGE_OF_ORDER, $staff_id, 'collect');

        return $this->response->paginator($orders, new Client_StationInvoiceCollectOrderTransformer());
    }

    public function bonus(Request $request, PreorderRepositoryContract $preorderRepo)
    {
        $date = $request->input('date') ?: null;
        if (!$date) {
            $start_date = '2016-9';
            $month_data[0] = $start_date;
            $months = Carbon::now()->subMonth()->diffInMonths(Carbon::create(2016, 9));
            for ($i = 1; $i <= $months; $i++) {
                $month_data[$i] = Carbon::create(2016, 9)->addMonth($i)->format('Y-m');
            }
            return $this->response->array(['data' => $month_data]);
        }

        if (Carbon::parse($date) > Carbon::now()->subMonth()) {
            $this->response->errorNotFound();
        }

        $preorders = $preorderRepo->getAll(null, null, null, null, [PreorderProtocol::ORDER_STATUS_OF_SHIPPING, PreorderProtocol::ORDER_STATUS_OF_DONE], Carbon::parse($date)->startOfMonth(), Carbon::parse($date)->endOfMonth(), 'confirm_at');
        return ExcelService::downloadPreorderBounce($preorders, '燕塘优鲜达威臣销售提成-' . $date);
    }


}


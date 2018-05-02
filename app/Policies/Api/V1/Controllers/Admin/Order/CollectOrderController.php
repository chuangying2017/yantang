<?php namespace App\Api\V1\Controllers\Admin\Order;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Transformers\Admin\Order\AdminCollectOrderTransformer;
use App\Models\Collect\CollectOrder;
use App\Models\Order\Order;
use App\Repositories\Order\CollectOrderRepository;
use App\Services\Chart\ExcelService;
use Illuminate\Http\Request;

class CollectOrderController extends Controller
{

    protected $collectOrderRepo;
    const PER_PAGE = 20;

    public function __construct(CollectOrderRepository $collectOrderRepo)
    {
        $this->collectOrderRepo = $collectOrderRepo;
    }

    public function index(Request $request)
    {
        $phone = $request->input('phone') ?: null;
        $start_time = $request->input('start_time') ?: null;
        $end_time = $request->input('end_time') ?: null;
        $order_no = $request->input('order_no');
        $residence_id = $request->input('residence_id');
        $export = $request->input('export', null);
        $per_page = self::PER_PAGE;
        if ($export == 'all') {
            $per_page = null;
        }

        $station_id = null;

        $orders = $this->collectOrderRepo->getAllPaid($station_id, $order_no, $phone, $start_time, $end_time, $residence_id, $per_page);

        if ($export == 'all') {
            return ExcelService::downCollectOrder($orders);
        }
        return $this->response->paginator($orders, new AdminCollectOrderTransformer());
    }

    public function show($order_id)
    {
        $order = CollectOrder::with(['staff', 'sku', 'address', 'order', 'staff.station', 'order.promotions.promotion'])
            ->where('id', $order_id)
            ->firstOrFail();

        return $this->response->item($order, new AdminCollectOrderTransformer());
    }

}

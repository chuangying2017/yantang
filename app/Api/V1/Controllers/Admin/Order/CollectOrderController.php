<?php namespace App\Api\V1\Controllers\Admin\Order;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Transformers\Admin\Order\AdminCollectOrderTransformer;
use App\Models\Collect\CollectOrder;
use App\Repositories\Order\CollectOrderRepository;
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
        $time_name = $request->input('time_name', 'created_at');

        if ($request->input('export') == 'all') {
            // $orders = $this->collectOrderRepo->getAll($station_id, $order_no, $pay_order_no, $phone, $status, $start_time, $end_time, $time_name);
            // return ExcelService::downPreorder($orders);
        }

        $query = CollectOrder::query();

        if (!is_null($phone)) {
            $query->whereHas('address', function ($query) use ($phone) {
                $query->where('phone', $phone);
            });
        }
        $query->whereNotNull('pay_at');
        if (!is_null($start_time)) {
            $query->where('pay_at', '>=', $start_time);
        }

        if (!is_null($end_time)) {
            $query->where('pay_at', '<=', $end_time);
        }

        $query->orderBy('pay_at', 'DESC');

        $orders = $query->paginate(self::PER_PAGE);
        $orders->load('staff');

        return $this->response->paginator($orders, new AdminCollectOrderTransformer());
    }

    public function show($order_id)
    {
        $order = CollectOrder::with(['staff', 'sku', 'address', 'order'])
            ->where('id', $order_id)
            ->firstOrFail();

        return $this->response->item($order, new AdminCollectOrderTransformer());
    }

}

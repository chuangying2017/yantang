<?php

namespace App\Api\V1\Controllers\Admin\Order;

use App\Repositories\Order\SubscribeOrderRepository;
use App\Services\Order\OrderProtocol;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SubscribeOrderController extends Controller {

    /**
     * @var SubscribeOrderRepository
     */
    private $orderRepo;

    /**
     * SubscribeOrderController constructor.
     * @param SubscribeOrderRepository $orderRepo
     */
    public function __construct(SubscribeOrderRepository $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function index(Request $request)
    {
        $export = $request->input('export') ?: 0;
        $status = $request->input('status') ?: null;
        $keyword = $request->input('keyword') ?: null;
        $start_time = $request->input('start_time') ?: null;
        $end_time = $request->input('end_time') ?: null;
        $time_name = $request->input('time_name') ?: 'pay_at';
        $order_by = $request->input('order_by') ?: 'created_at';
        $sort = $request->input('sort') ?: 'desc';
        $per_page = $request->input('per_page') ?: OrderProtocol::ORDER_PER_PAGE;

        if (!$export) {
            $orders = $this->orderRepo->getPaginatedOrders($status, $keyword, $order_by, $sort, $per_page, $start_time, $end_time, $time_name);

            return $orders;
        }

        #todo 导出excel

        $orders = $this->orderRepo->getAllOrders($start_time, $end_time, $time_name, $status, $keyword, $order_by, $sort);
    }


}

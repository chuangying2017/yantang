<?php

namespace App\Api\V1\Controllers\Admin\Order;

use App\Api\V1\Transformers\Mall\ClientOrderTransformer;
use App\Repositories\Order\SubscribeOrderRepository;
use App\Services\Order\OrderManageContract;
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
        $status = $request->input('status') ?: null;
        $keyword = $request->input('keyword') ?: null;
        $start_time = $request->input('start_time') ?: null;
        $end_time = $request->input('end_time') ?: null;
        $time_name = $request->input('time_name') ?: 'pay_at';
        $order_by = $request->input('order_by') ?: 'created_at';
        $sort = $request->input('sort') ?: 'desc';
        $per_page = $request->input('per_page') ?: OrderProtocol::ORDER_PER_PAGE;

        $orders = $this->orderRepo->getPaginatedOrders($status, $keyword, $order_by, $sort, $per_page, $start_time, $end_time, $time_name);

        return $this->response->paginator($orders, new ClientOrderTransformer());
    }

    public function destroy(Request $request, $order_id, OrderManageContract $orderManager)
    {
        $success = $orderManager->cancelUnpaidOrder($order_id);

        return $this->response->noContent();
    }

}

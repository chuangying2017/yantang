<?php

namespace App\Api\V1\Controllers\Admin\Order;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Transformers\Admin\Order\AdminMallOrderTransformer;
use App\Repositories\Order\AdminSpecialCampaignOrderRepository;
use App\Repositories\Order\MallAdminOrderRepository;
use Illuminate\Http\Request;

use App\Http\Requests;

class SpecialOrderController extends Controller {

    /**
     * @var MallAdminOrderRepository
     */
    private $orderRepo;

    /**
     * OrderController constructor.
     * @param MallAdminOrderRepository $orderRepo
     */
    public function __construct(AdminSpecialCampaignOrderRepository $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status = $request->input('status');
        $keyword = $request->input('keyword');
        $order_by = $request->input('order_by', 'created_at');
        $sort = $request->input('sort', 'desc');

        $orders = $this->orderRepo->getPaginatedOrders($status, $keyword, $order_by, $sort);

        return $this->response->paginator($orders, new AdminMallOrderTransformer());
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($order_no)
    {
        $order = $this->orderRepo->getOrder($order_no, true);

        return $this->response->item($order, new AdminMallOrderTransformer());
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $order_no)
    {
        $status =$request->input('status');
        $order = $this->orderRepo->updateOrderStatus($order_no, $status);

        return $this->response->item($order, new AdminMallOrderTransformer());
    }

}

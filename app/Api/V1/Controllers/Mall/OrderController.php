<?php namespace App\Api\V1\Controllers\Mall;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Transformers\Mall\ClientOrderTransformer;
use App\Repositories\Order\ClientOrderRepositoryContract;
use App\Repositories\Order\MallClientOrderRepository;
use App\Services\Order\OrderGenerator;
use App\Services\Order\OrderManageContract;
use Illuminate\Http\Request;

use App\Http\Requests;

class OrderController extends Controller {

    /**
     * @var ClientOrderRepositoryContract
     */
    private $clientOrderRepo;

    /**
     * OrderController constructor.
     * @param ClientOrderRepositoryContract $clientOrderRepo
     */
    public function __construct(MallClientOrderRepository $clientOrderRepo)
    {
        $this->clientOrderRepo = $clientOrderRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = $this->clientOrderRepo->getPaginatedOrders();

        return $this->response->paginator($orders, new ClientOrderTransformer());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderGenerator $orderGenerator, Request $request)
    {
//        try {
            $temp_order_id = $request->input(['temp_order_id']);

            $order = $orderGenerator->confirm($temp_order_id);

            return $this->response->item($order, new ClientOrderTransformer())->setStatusCode(201);
//        } catch (\Exception $e) {
//            $this->response->errorBadRequest($e->getMessage());
//        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($order_no)
    {
        $order = $this->clientOrderRepo->getOrder($order_no, true);
        return $this->response->item($order, new ClientOrderTransformer());
    }

    public function update($order_id, Request $request, OrderManageContract $orderManage)
    {
        $order = $orderManage->orderDone($order_id);

        return $this->response->item($order, new ClientOrderTransformer());
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderManageContract $orderManage, Request $request, $id)
    {
        $order = $orderManage->orderCancel($id, $request->input('memo'), $request->input('order_skus'));

        return $this->response->item($order, new ClientOrderTransformer())->setStatusCode(204);
    }
}

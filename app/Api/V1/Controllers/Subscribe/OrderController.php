<?php

namespace App\Api\V1\Controllers\Subscribe;

use App\API\V1\Controllers\Controller;
use App\Api\V1\Requests\SubscribeOrderRequest;
use App\Api\V1\Transformers\Mall\ClientOrderTransformer;
use App\Repositories\Order\PreorderOrderRepository;
use App\Services\Order\Checkout\OrderCheckoutService;
use App\Services\Order\OrderGenerator;
use App\Services\Order\OrderManageContract;
use App\Services\Order\OrderProtocol;
use App\Services\Pay\Pingxx\PingxxProtocol;
use Illuminate\Http\Request;

use App\Http\Requests;

class OrderController extends Controller {

    /**
     * @var PreorderOrderRepository
     */
    private $orderRepo;

    /**
     * OrderController constructor.
     * @param PreorderOrderRepository $orderRepo
     */
    public function __construct(PreorderOrderRepository $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function index()
    {
        $orders = $this->orderRepo->getPaginatedOrders();

        return $this->response->paginator($orders, new ClientOrderTransformer());
    }

    public function store(SubscribeOrderRequest $request, OrderGenerator $orderGenerator, OrderCheckoutService $orderCheckout)
    {
//        try {
            $skus = $request->input('skus');
            $weekday_type = $request->input('weekday_type');
            $daytime = $request->input('daytime');
            $start_time = $request->input('start_time');
            $address_id = $request->input('address_id');
            $pay_channel = $request->input('channel') ?: PingxxProtocol::PINGXX_WAP_CHANNEL_WECHAT;

            $order = $orderGenerator->subscribe(access()->id(), $skus, $weekday_type, $daytime, $start_time, $address_id);

            $charge = $orderCheckout->checkout($order['id'], OrderProtocol::BILLING_TYPE_OF_MONEY, $pay_channel);

            return $this->response->item($order, new ClientOrderTransformer())->setMeta(['charge' => $charge])->setStatusCode(201);;
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
    public function show($id)
    {
        $order = $this->orderRepo->getOrder($id, true);

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
        $orderManage->orderCancel($id, $request->input('memo'));

        return $this->response->noContent();
    }

}

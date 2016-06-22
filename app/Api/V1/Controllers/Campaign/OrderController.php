<?php

namespace App\Api\V1\Controllers\Campaign;

use App\Api\V1\Transformers\Mall\ClientOrderTransformer;
use App\Repositories\Order\CampaignOrderRepository;
use App\Services\Order\Checkout\OrderCheckoutService;
use App\Services\Order\OrderGenerator;
use App\Services\Order\OrderManageContract;
use App\Services\Order\OrderProtocol;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OrderController extends Controller {

    /**
     * @var CampaignOrderRepository
     */
    private $orderRepo;

    /**
     * OrderController constructor.
     * @param CampaignOrderRepository $orderRepo
     */
    public function __construct(CampaignOrderRepository $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = $this->orderRepo->getAllOrders();

        return $this->response->paginator($orders, new ClientOrderTransformer());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        try {
            $skus = $request->input(['product_skus']);
            $pay_channel = $request->input('channel');
//            $campaign_id = $request->input('campaign');

            $order = app()->make(OrderGenerator::class)->buy(access()->id(), $skus, null, OrderProtocol::ORDER_TYPE_OF_CAMPAIGN);

            $charge = app()->make(OrderCheckoutService::class)->checkout($order['id'], OrderProtocol::BILLING_TYPE_OF_MONEY, $pay_channel);
            return $this->response->array(['data' => $charge]);
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
    public function destroy(Request $request, $id)
    {
        app()->make(OrderManageContract::class)->orderCancel($id, $request->input('memo'));
    }
}

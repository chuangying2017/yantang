<?php

namespace App\Api\V1\Controllers\Campaign;

use App\Api\V1\Requests\Campaign\CampaignOrderRequest;
use App\Api\V1\Transformers\Mall\ClientOrderTransformer;
use App\Repositories\Order\CampaignOrderRepository;
use App\Repositories\Promotion\Campaign\EloquentCampaignRepository;
use App\Services\Order\Checkout\OrderCheckoutService;
use App\Services\Order\OrderGenerator;
use App\Services\Order\OrderManageContract;
use App\Services\Order\OrderProtocol;
use App\Services\Pay\Pingxx\PingxxProtocol;
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
        $orders = $this->orderRepo->getPaginatedOrders();

        return $this->response->paginator($orders, new ClientOrderTransformer());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderGenerator $orderGenerator, OrderCheckoutService $orderCheckout, EloquentCampaignRepository $campaignRepo, CampaignOrderRequest $request)
    {
        try {
            $campaign_id = $request->input(['campaign']);
            $pay_channel = $request->input('channel') ?: PingxxProtocol::PINGXX_WAP_CHANNEL_WECHAT;

            $order = $orderGenerator->buySpecialCampaign(access()->id(), $campaign_id, $campaignRepo);

            $charge = $orderCheckout->checkout($order['id'], OrderProtocol::BILLING_TYPE_OF_MONEY, $pay_channel);

            return $this->response->item($order, new ClientOrderTransformer())->setMeta(['charge' => $charge])->setStatusCode(201);;
        } catch (\Exception $e) {
            $this->response->errorBadRequest($e->getMessage());
        }
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

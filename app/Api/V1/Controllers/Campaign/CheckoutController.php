<?php

namespace App\Api\V1\Controllers\Campaign;

use App\Repositories\Order\CampaignOrderRepository;
use App\Services\Order\Checkout\OrderCheckoutService;
use App\Services\Order\OrderProtocol;
use App\Services\Pay\Pingxx\PingxxProtocol;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CheckoutController extends Controller {

    /**
     * @var CampaignOrderRepository
     */
    private $orderRepo;
    /**
     * @var OrderCheckoutService
     */
    private $checkoutService;

    /**
     * CheckoutController constructor.
     * @param CampaignOrderRepository $orderRepo
     * @param OrderCheckoutService $checkoutService
     */
    public function __construct(CampaignOrderRepository $orderRepo, OrderCheckoutService $checkoutService)
    {
        $this->orderRepo = $orderRepo;
        $this->checkoutService = $checkoutService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->response->array(['data' => PingxxProtocol::agent()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $order_no)
    {
        $pay_channel = $request->input('channel') ?: PingxxProtocol::PINGXX_WAP_CHANNEL_WECHAT;

        $order = $this->orderRepo->getOrder($order_no);

        $charge = $this->checkoutService->checkout($order['id'], OrderProtocol::BILLING_TYPE_OF_MONEY, $pay_channel);

        return $this->response->array(['data' => $charge]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

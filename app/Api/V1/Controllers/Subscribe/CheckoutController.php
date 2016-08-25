<?php

namespace App\Api\V1\Controllers\Subscribe;

use App\Repositories\Order\PreorderOrderRepository;
use App\Services\Order\Checkout\OrderCheckoutService;
use App\Services\Order\OrderProtocol;
use App\Services\Pay\Pingxx\PingxxProtocol;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CheckoutController extends Controller {

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
    public function store(PreorderOrderRepository $orderRepo, OrderCheckoutService $checkoutService, Request $request, $order_no)
    {
        try {
            $pay_channel = $request->input('channel') ?: PingxxProtocol::PINGXX_WAP_CHANNEL_WECHAT;

            $order = $orderRepo->getOrder($order_no);

            $charge = $checkoutService->checkout($order['id'], OrderProtocol::BILLING_TYPE_OF_MONEY, $pay_channel);

            return $this->response->array(['data' => $charge]);
        } catch (\Exception $e) {
            $this->response->error($e->getMessage(), 400);
        }

    }
}

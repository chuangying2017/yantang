<?php

namespace App\Api\V1\Controllers\Mall;

use App\Services\Order\Checkout\OrderCheckoutContract;
use App\Services\Order\OrderProtocol;
use App\Services\Pay\Pingxx\PingxxProtocol;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CheckoutController extends Controller {

    /**
     * @var OrderCheckoutContract
     */
    private $checkout;

    /**
     * CheckoutController constructor.
     * @param OrderCheckoutContract $checkout
     */
    public function __construct(OrderCheckoutContract $checkout)
    {
        $this->checkout = $checkout;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $channels = PingxxProtocol::agent(PingxxProtocol::AGENT_OF_MOBILE);

        return $this->response->array(['data' => $channels]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $order_id)
    {
        $pay_channel = $request->input('channel');

        $charge = $this->checkout->checkout($order_id, OrderProtocol::BILLING_TYPE_OF_MONEY, $pay_channel);

        return $this->response->array(['data' => $charge]);
    }

}

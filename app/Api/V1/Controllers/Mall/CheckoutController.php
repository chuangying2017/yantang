<?php

namespace App\Api\V1\Controllers\Mall;

use App\API\V1\Controllers\Controller;
use App\Repositories\Order\MallClientOrderRepository;
use App\Services\Order\Checkout\OrderCheckoutContract;
use App\Services\Order\OrderProtocol;
use App\Services\Pay\Pingxx\PingxxProtocol;
use Illuminate\Http\Request;

use App\Http\Requests;

class CheckoutController extends Controller {

    /**
     * @var OrderCheckoutContract
     */
    private $checkout;
    /**
     * @var MallClientOrderRepository
     */
    private $orderRepo;

    /**
     * CheckoutController constructor.
     * @param OrderCheckoutContract $checkout
     */
    public function __construct(OrderCheckoutContract $checkout, MallClientOrderRepository $orderRepo)
    {
        $this->checkout = $checkout;
        $this->orderRepo = $orderRepo;
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
        $pay_channel = $request->input('channel', PingxxProtocol::PINGXX_WAP_CHANNEL_WECHAT);

        $order = $this->orderRepo->getOrder($order_id);

        $charge = $this->checkout->checkout($order['id'], OrderProtocol::BILLING_TYPE_OF_MONEY, $pay_channel);

        return $this->response->array(['data' => $charge]);
    }

}

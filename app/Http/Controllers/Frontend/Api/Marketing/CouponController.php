<?php

namespace App\Http\Controllers\Frontend\Api\Marketing;

use App\Http\Transformers\TicketTransformer;
use App\Services\Marketing\Items\Coupon\CouponDistributor;
use App\Services\Marketing\Items\Coupon\UseCoupon;
use App\Services\Orders\OrderGenerator;

use App\Http\Requests\Frontend\Api\MarketingRequest as Request;

use App\Http\Requests;

class CouponController extends MarketingController {

    /**
     * @var OrderGenerator
     */
    private $orderGenerator;

    /**
     * CouponController constructor.
     * @param CouponDistributor $distributor
     * @param UseCoupon $useCoupon
     * @param OrderGenerator $orderGenerator
     */
    public function __construct(CouponDistributor $distributor, UseCoupon $useCoupon, OrderGenerator $orderGenerator)
    {
        parent::__construct($distributor, $useCoupon);
        $this->orderGenerator = $orderGenerator;
    }

    /**
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $tickets = parent::index($request);

        return $this->response->collection($tickets, new TicketTransformer());
    }


    public function exchange(Request $request)
    {
        try {
            $uuid = $request->input('uuid');
            $coupon_id = $request->input('coupon_id');

            $coupon = $this->using->show($coupon_id);

            $this->orderGenerator->setMarketUsing($this->using);
            $order_info = $this->orderGenerator->requestDiscount($coupon, $uuid);

            return $order_info;
        } catch (\Exception $e) {
            $this->response->errorBadRequest('优惠券不存在');
        }
    }


}

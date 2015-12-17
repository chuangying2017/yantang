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

    public function exchange(Request $request)
    {
        try {
            $uuid = $request->input('uuid');
            $ticket_id = $request->input('ticket_id', []);

            $coupon = $this->using->show(to_array($ticket_id));

            $this->orderGenerator->setMarketUsing($this->using);
            $order_info = $this->orderGenerator->requestDiscount($coupon, $uuid);

            return $order_info;
        } catch (\Exception $e) {
            return $e->getTrace();
            $this->response->errorBadRequest('优惠券不存在');
        }
    }


}

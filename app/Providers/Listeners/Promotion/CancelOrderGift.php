<?php

namespace App\Listeners\Promotion;

use App\Events\Order\OrderIsCancel;
use App\Repositories\Promotion\TicketRepositoryContract;
use App\Services\Promotion\CouponService;
use App\Services\Promotion\PromotionProtocol;
use App\Services\Promotion\RedEnvelopeService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CancelOrderGift {

    /**
     * @var CouponService
     */
    private $couponService;

    /**
     * @var RedEnvelopeService
     */
    private $redEnvelopeService;

    /**
     * CancelOrderGift constructor.
     * @param CouponService $couponService
     * @param RedEnvelopeService $redEnvelopeService
     */
    public function __construct(CouponService $couponService, RedEnvelopeService $redEnvelopeService)
    {
        $this->couponService = $couponService;
        $this->redEnvelopeService = $redEnvelopeService;
    }

    /**
     * Handle the event.
     *
     * @param  OrderIsCancel $event
     * @return void
     */
    public function handle(OrderIsCancel $event)
    {
        $order = $event->order;

        //取消订单送券
        $this->couponService->cancelByResource(PromotionProtocol::TICKET_RESOURCE_OF_ORDER, $order['id']);

        //取消红包及其派送的优惠券
        $this->redEnvelopeService->cancelForOrder($order['id'], $order['order_type'], $this->couponService);
    }

}

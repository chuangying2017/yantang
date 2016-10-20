<?php

namespace App\Listeners\Promotion;

use App\Events\Order\OrderIsPaid;
use App\Repositories\Auth\User\EloquentUserRepository;
use App\Repositories\Order\ClientOrderRepository;
use App\Repositories\Promotion\Coupon\CouponRepositoryContract;
use App\Services\Order\OrderManageService;
use App\Services\Order\OrderProtocol;
use App\Services\Promotion\CouponService;
use App\Services\Promotion\PromotionProtocol;
use App\Services\Promotion\RedEnvelopeService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DispatchSubscribeOrderGift {

    /**
     * @var OrderManageService
     */
    private $orderManageService;

    /**
     * @var RedEnvelopeService
     */
    private $redEnvelopeService;

    /**
     * DispatchSubscribeOrderGift constructor.
     * @param OrderManageService $orderManageService
     * @param RedEnvelopeService $redEnvelopeService
     */
    public function __construct(
        OrderManageService $orderManageService,
        RedEnvelopeService $redEnvelopeService
    )
    {
        $this->orderManageService = $orderManageService;
        $this->redEnvelopeService = $redEnvelopeService;
    }

    /**
     * Handle the event.
     *
     * @param  OrderIsPaid $event
     * @return void
     */
    public function handle(OrderIsPaid $event)
    {
        try {
            $order = $event->order;
            $user_id = $order->user_id;

            $this->redEnvelopeService->dispatchForOrder($order['id'], $user_id, $order['order_type']);
        } catch (\Exception $e) {
            \Log::error($e);
        }
    }


}

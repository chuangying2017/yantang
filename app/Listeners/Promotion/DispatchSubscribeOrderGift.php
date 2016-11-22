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
        RedEnvelopeService $redEnvelopeService,
        CouponService $couponService,
        CouponRepositoryContract $couponRepo
    )
    {
        $this->orderManageService = $orderManageService;
        $this->redEnvelopeService = $redEnvelopeService;
        $this->couponRepo = $couponRepo;
        $this->couponService = $couponService;
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

            //送红包
            $this->redEnvelopeService->dispatchForOrder($order['id'], $user_id, $order['order_type']);

            //送券
            $coupons = $this->couponRepo->getAllByQualifyTye(PromotionProtocol::QUALI_TYPE_OF_PRE_ORDER);
            if (count($coupons)) {
                foreach ($coupons as $coupon) {
                    $result = $this->couponService->dispatchWithoutCheck($user_id, $coupon['id'], PromotionProtocol::TICKET_RESOURCE_OF_ORDER, $order['id']);
                }
            }

        } catch (\Exception $e) {
            \Log::error($e);
        }
    }


}

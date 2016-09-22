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
     * @var EloquentUserRepository
     */
    private $userRepo;

    /**
     * @var OrderManageService
     */
    private $orderManageService;

    /**
     * @var CouponRepositoryContract
     */
    private $couponRepo;

    /**
     * @var CouponService
     */
    private $couponService;

    /**
     * DispatchSubscribeOrderGift constructor.
     * @param EloquentUserRepository $userRepo
     * @param OrderManageService $orderManageService
     * @param CouponService $couponService
     * @param CouponRepositoryContract $couponRepo
     * @param RedEnvelopeService $redEnvelopeService
     */
    public function __construct(
        EloquentUserRepository $userRepo,
        OrderManageService $orderManageService,
        CouponService $couponService,
        CouponRepositoryContract $couponRepo,
        RedEnvelopeService $redEnvelopeService
    )
    {
        $this->userRepo = $userRepo;
        $this->orderManageService = $orderManageService;
        $this->couponRepo = $couponRepo;
        $this->couponService = $couponService;
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

            if ($this->orderManageService->orderIsFirstPaid($order)) {
                $coupons = $this->couponRepo->getAllByQualifyTye(PromotionProtocol::QUALI_TYPE_OF_FIRST_PRE_ORDER);
                if (count($coupons)) {
                    foreach ($coupons as $coupon) {
                        $result = $this->couponService->dispatch($this->userRepo->setUser($user_id), $coupon, PromotionProtocol::TICKET_RESOURCE_OF_ORDER, $order['id']);
                    }
                }
            }

            if (OrderProtocol::ORDER_TYPE_OF_SUBSCRIBE == $order['order_type']) {
                $this->redEnvelopeService->dispatchForOrder($order['id'], $user_id);
            }

        } catch (\Exception $e) {
            \Log::error($e);
        }
    }

    /**
     * @var RedEnvelopeService
     */
    private $redEnvelopeService;


}

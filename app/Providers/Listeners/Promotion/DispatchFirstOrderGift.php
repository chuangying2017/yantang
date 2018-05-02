<?php

namespace App\Listeners\Promotion;

use App\Events\Order\FirstOrderIsPaid;
use App\Repositories\Auth\User\EloquentUserRepository;
use App\Repositories\Promotion\Coupon\CouponRepositoryContract;
use App\Services\Promotion\CouponService;
use App\Services\Promotion\PromotionProtocol;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DispatchFirstOrderGift {

    /**
     * @var CouponRepositoryContract
     */
    private $couponRepo;
    /**
     * @var EloquentUserRepository
     */
    private $userRepo;
    /**
     * @var CouponService
     */
    private $couponService;
    
    /**
     * Create the event listener.
     *
     * @param CouponService $couponService
     * @param CouponRepositoryContract $couponRepo
     * @param EloquentUserRepository $userRepo
     */
    public function __construct(CouponService $couponService, CouponRepositoryContract $couponRepo, EloquentUserRepository $userRepo)
    {
        $this->couponRepo = $couponRepo;
        $this->userRepo = $userRepo;
        $this->couponService = $couponService;
    }

    /**
     * Handle the event.
     *
     * @param  FirstOrderIsPaid $event
     * @return void
     */
    public function handle(FirstOrderIsPaid $event)
    {
        try {
            $order = $event->order;
            $user_id = $order['user_id'];
            $coupons = $this->couponRepo->getAllByQualifyTye(PromotionProtocol::QUALI_TYPE_OF_FIRST_PRE_ORDER);
            if (count($coupons)) {
                foreach ($coupons as $coupon) {
                    $result = $this->couponService->dispatch($this->userRepo->setUser($user_id), $coupon, PromotionProtocol::TICKET_RESOURCE_OF_ORDER, $order['id']);
                }
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }
    }


}

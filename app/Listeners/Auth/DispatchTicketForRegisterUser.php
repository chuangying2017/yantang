<?php

namespace App\Listeners\Auth;

use App\Events\Auth\UserRegister;
use App\Repositories\Promotion\Coupon\CouponRepositoryContract;
use App\Services\Promotion\PromotionProtocol;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\Promotion\CouponService;

class DispatchTicketForRegisterUser {

    /**
     * @var CouponRepositoryContract
     */
    private $couponRepo;

    /**
     * @var CouponService
     */
    private $couponService;

    /**
     * Create the event listener.
     *
     * @return void
     */

    public function __construct(CouponService $couponService, CouponRepositoryContract $couponRepo)
    {
        $this->couponRepo = $couponRepo;
        $this->couponService = $couponService;
    }

    /**
     * Handle the event.
     *
     * @param  UserRegister $event
     * @return void
     */
    public function handle(UserRegister $event)
    {
        $user = $event->user;

        try {
            //送券
            $coupons = $this->couponRepo->getAllByQualifyTye(PromotionProtocol::QUALI_TYPE_OF_REGISTER_USER);
            if (count($coupons)) {
                foreach ($coupons as $coupon) {
                    $result = $this->couponService->dispatchWithoutCheck($user['id'], $coupon['id']);
                }
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }
    }
}

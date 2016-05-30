<?php namespace App\Repositories\Promotion\Coupon;


use App\Repositories\Promotion\PromotionRepositoryContract;

interface CouponRepositoryContract extends PromotionRepositoryContract {

    public function getCouponsById($coupon_ids);
}

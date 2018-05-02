<?php namespace App\Repositories\Promotion\Coupon;


use App\Repositories\Promotion\PromotionRepositoryContract;
use App\Repositories\Promotion\PromotionSupportRepositoryContract;

interface CouponRepositoryContract extends PromotionRepositoryContract {

    public function getCouponsById($coupon_ids, $with_detail = true);
}

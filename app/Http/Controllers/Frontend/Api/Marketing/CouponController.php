<?php

namespace App\Http\Controllers\Frontend\Api\Marketing;

use App\Services\Marketing\Exceptions\MarketingItemDistributeException;
use App\Services\Marketing\Items\Coupon\CouponDistributor;
use App\Services\Marketing\Items\Coupon\UseCoupon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CouponController extends MarketingController {

    /**
 * @var CouponDistributor
 */
    private $distributor;

    /**
     * @param CouponDistributor $distributor
     */
    public function __construct(CouponDistributor $distributor, UseCoupon $useCoupon)
    {
        parent::__construct($distributor, $useCoupon);
    }


}

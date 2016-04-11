<?php namespace App\Services\Marketing\Items\Coupon;


use App\Services\Marketing\Exceptions\CouponValidationException;
use App\Services\Marketing\MarketingInterface;
use App\Services\Marketing\MarketingItemManager;
use App\Services\Marketing\MarketingProtocol;
use App\Services\Marketing\MarketingRepository;

class CouponManager extends MarketingItemManager implements MarketingInterface {

    public function __construct()
    {
        $this->setResourceType(MarketingProtocol::TYPE_OF_COUPON);
    }

    public function create($input)
    {
        try {
            $coupon_data = self::contentFilter($input, MarketingProtocol::TYPE_OF_COUPON);
            $limit_data = self::limitFilter($input);
            $coupon = MarketingRepository::storeCoupon($coupon_data, $limit_data);

            return $coupon;
        } catch (CouponValidationException $e) {
            throw $e;
        }
    }

    public function update($coupon_id, $input)
    {
        try {
            $coupon_data = self::contentFilter($input, MarketingProtocol::TYPE_OF_COUPON);
            $limit_data = self::limitFilter($input);
            $coupon = MarketingRepository::updateCoupon($coupon_id, $coupon_data, $limit_data);

            return $coupon;
        } catch (CouponValidationException $e) {
            throw $e;
        }
    }

    public function lists($status = null, $pagination = null)
    {
        $coupons = MarketingRepository::listsCoupon($status, $pagination);

        return $coupons;
    }

    public function show($coupon_id)
    {
        return MarketingRepository::queryFullCoupon($coupon_id);
    }

}

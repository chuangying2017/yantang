<?php namespace App\Services\Marketing;
class MarketingProtocol {

    //Coupon
    const COUPON_TYPE_OF_CASH = 'cash';
    const COUPON_TYPE_OF_DISCOUNT = 'discount';
    const COUPON_DEFAULT_NAME = '通用优惠券';

    const STATUS_OF_PENDING = 'pending';
    const STATUS_OF_FROZEN = 'frozen';
    const STATUS_OF_USED = 'used';
    const STATUS_OF_EXPIRED = 'expired';

    /*
     * ------------------------------------------
     * Discount Limit
     * ------------------------------------------
     */
    const QUANTITY_NO_LIMIT = -1;
    const AMOUNT_NO_LIMIT = 0;
    const CATEGORY_NO_LIMIT = 0;
    const PRODUCT_LIMIT = 0;
    const CAN_NOT_MULTI_USE = 0;
    const CAN_MULTI_USE = 1;
    //resources
    const TYPE_OF_COUPON = 'Coupon';
    const TYPE_OF_COUPON_CODE = 'CouponCode';
    const TYPE_OF_GIFT = 'Gift';
    const TYPE_OF_CASH_BACK = 'CashBack';
    const LIMIT_NO_EFFECT_TIME = null;
    const LIMIT_NO_EXPIRE_TIME = null;

    public static function limitType($type = null)
    {
        return array_get(
            [
                self::TYPE_OF_COUPON => '优惠券',
                self::TYPE_OF_COUPON_CODE => '优惠码',
                self::TYPE_OF_GIFT => '赠品',
                self::TYPE_OF_CASH_BACK => '订单返现',
            ],
            $type,
            null
        );
    }


}

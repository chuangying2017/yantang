<?php namespace App\Services\Marketing;
class MarketingProtocol {

    //Coupon
    const COUPON_TYPE_OF_CASH = 'cash';
    const COUPON_TYPE_OF_DISCOUNT = 'discount';
    const COUPON_DEFAULT_NAME = '通用优惠券';

    const DISCOUNT_TYPE_OF_CASH = 'cash';
    const DISCOUNT_TYPE_OF_DISCOUNT = 'discount';


    const STATUS_OF_PENDING = 'pending';
    const STATUS_OF_FROZEN = 'frozen';
    const STATUS_OF_USED = 'used';
    const STATUS_OF_EXPIRED = 'expired';

    const LENGTH_OF_TICKET_NO = 10;

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
    const TYPE_OF_COUPON = 'App\Models\Coupon';
    const TYPE_OF_COUPON_CODE = 'App\Models\CouponCode';
    const TYPE_OF_GIFT = 'App\Models\Gift';
    const TYPE_OF_CASH_BACK = 'App\Models\CashBack';
    const LIMIT_NO_EFFECT_TIME = null;
    const LIMIT_NO_EXPIRE_TIME = null;

    public static function limitType($type = null)
    {
        return array_get(
            [
                self::TYPE_OF_COUPON      => '优惠券',
                self::TYPE_OF_COUPON_CODE => '优惠码',
                self::TYPE_OF_GIFT        => '赠品',
                self::TYPE_OF_CASH_BACK   => '订单返现',
            ],
            $type,
            null
        );
    }

    public static function discountType($type = null, $key = false)
    {
        return
            $key
                ? [self::DISCOUNT_TYPE_OF_CASH, self::DISCOUNT_TYPE_OF_DISCOUNT]
                : array_get([
                self::DISCOUNT_TYPE_OF_CASH     => '现金券',
                self::DISCOUNT_TYPE_OF_DISCOUNT => '打折券',
            ], $type, null);
    }

    public static function limitToArray($limits, $default = null)
    {
        return is_array($limits) ? $limits : (($limits) ? explode(',', $limits) : $default);
    }

    public static function checkLimit($limits, $value)
    {
        $limits = self::limitToArray($limits);

        return is_null($limits) ? true : in_array($value, $limits);
    }

}

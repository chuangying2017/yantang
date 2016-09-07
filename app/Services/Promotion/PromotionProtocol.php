<?php namespace App\Services\Promotion;

use App\Models\Promotion\Campaign;
use App\Models\Promotion\Coupon;
use App\Services\Promotion\Rule\Benefit\AmountBenefit;
use App\Services\Promotion\Rule\Qualification\AllUserQualification;
use App\Services\Promotion\Rule\Usage\AllItemsUsage;

class PromotionProtocol {

    const TICKET_PER_PAGE = 10;

    const BASE_OF_PERCENTAGE = 10000;

    const MODEL_OF_COUPON = Coupon::class;
    const MODEL_OF_CAMPAIGN = Campaign::class;

    const ACTION_OF_ADD = 1;
    const ACTION_OF_SUB = 2;
    const ACTION_OF_EQU = 3;

    const TYPE_OF_COUPON = 'coupon';
    const TYPE_OF_MALL_CAMPAIGN = 'mall_campaign';
    const TYPE_OF_SPECIAL_CAMPAIGN = 'spec_campaign';

    const RULE_TYPE_OF_CAMPAIGN = 'campaign';
    const RULE_TYPE_OF_COUPON = 'coupon';

    const NAME_OF_COUNTER_TOTAL = 'total';
    const NAME_OF_COUNTER_DISPATCH = 'dispatch';
    const NAME_OF_COUNTER_USED = 'used';
    const NAME_OF_COUNTER_EFFECT_DAY = 'effect_days';


    const ITEM_TYPE_OF_PRODUCT = 'product';
    const ITEM_TYPE_OF_SKU = 'sku';
    const ITEM_TYPE_OF_CATEGORY = 'cat';
    const ITEM_TYPE_OF_GROUP = 'group';
    const ITEM_TYPE_OF_BRAND = 'brand';
    const ITEM_TYPE_OF_ALL = 'all';

    const RANGE_TYPE_OF_AMOUNT = 'amount';
    const RANGE_TYPE_OF_QUANTITY = 'quantity';
    const RANGE_TYPE_OF_TOTAL_QUANTITY = 'total_quantity';

    const DISCOUNT_TYPE_OF_AMOUNT = 'amount';
    const DISCOUNT_TYPE_OF_EXPRESS = 'express';
    const DISCOUNT_TYPE_OF_CREDITS = 'credits';
    const DISCOUNT_TYPE_OF_SPECIAL_PRICE = 'price';
    const DISCOUNT_TYPE_OF_COUPON = 'coupon';
    const DISCOUNT_TYPE_OF_PRODUCT = 'product';


    const DISCOUNT_MODE_OF_DECREASE = 'decrease';
    const DISCOUNT_MODE_OF_PERCENTAGE = 'percentage';
    const DISCOUNT_MODE_OF_EQUAL = 'equal';
    const DISCOUNT_MODE_OF_MUL = 'mul';

    public static function discountContentIsAmount($type, $mode)
    {
        return
            in_array($type, [self::DISCOUNT_TYPE_OF_AMOUNT, self::DISCOUNT_TYPE_OF_SPECIAL_PRICE, self::DISCOUNT_TYPE_OF_EXPRESS]) &&
            in_array($mode, [self::DISCOUNT_MODE_OF_DECREASE, self::ACTION_OF_EQU])
                ? true : false;
    }

    public static function discountContentIsPercentage($mode)
    {
        return
            in_array($mode, [self::DISCOUNT_MODE_OF_PERCENTAGE])
                ? true : false;
    }


    const MULTI_TYPE_OF_ONLY = 0;
    const MULTI_TYPE_OF_CAN_MULTI = 1;

    const QUALI_TYPE_OF_USER = 'user';
    const QUALI_TYPE_OF_LEVEL = 'level';
    const QUALI_TYPE_OF_ROLE = 'role';
    const QUALI_TYPE_OF_ALL = 'all';
    const QUALI_TYPE_OF_GROUP = 'group';
    const QUALI_TYPE_OF_FIRST_ORDER = 'first_order';
    const QUALI_TYPE_OF_FIRST_PAID_ORDER = 'first_paid';


    public static function getQualifyType($name = null)
    {
        $data = [
            self::QUALI_TYPE_OF_ALL => '所有用户',
            self::QUALI_TYPE_OF_USER => '指定用户',
            self::QUALI_TYPE_OF_LEVEL => '指定用户等级',
            self::QUALI_TYPE_OF_ROLE => '指定用户角色',
            self::QUALI_TYPE_OF_FIRST_ORDER => '首单优惠',
            self::QUALI_TYPE_OF_FIRST_PAID_ORDER => '首单已支付优惠',
        ];

        return is_null($name) ? $data : array_get($data, $name, null);
    }

    public static function getItemType($name = null)
    {
        $data = [
            self::ITEM_TYPE_OF_PRODUCT => '指定商品',
            self::ITEM_TYPE_OF_SKU => '指定商品SKU',
            self::ITEM_TYPE_OF_CATEGORY => '指定分类',
            self::ITEM_TYPE_OF_GROUP => '指定分组',
            self::ITEM_TYPE_OF_BRAND => '指定品牌',
        ];

        return is_null($name) ? $data : $data[$name];
    }

    public static function getRangeType($name = null)
    {
        $data = [
            self::RANGE_TYPE_OF_AMOUNT => '指定金额',
            self::RANGE_TYPE_OF_QUANTITY => '指定数量',
        ];

        return is_null($name) ? $data : $data[$name];
    }

    public static function getDiscountType($name = null)
    {
        $data = [
            self::DISCOUNT_TYPE_OF_AMOUNT => '总额',
            self::DISCOUNT_TYPE_OF_EXPRESS => '运费',
            self::DISCOUNT_TYPE_OF_CREDITS => '积分',
            self::DISCOUNT_TYPE_OF_SPECIAL_PRICE => '特价',
            self::DISCOUNT_TYPE_OF_COUPON => '优惠券',
            self::DISCOUNT_TYPE_OF_PRODUCT => '赠品',
        ];

        return is_null($name) ? $data : $data[$name];
    }

    public static function getDiscountMode($name = null)
    {
        $data = [
            self::DISCOUNT_MODE_OF_DECREASE => '满减',
            self::DISCOUNT_MODE_OF_PERCENTAGE => '折扣',
            self::DISCOUNT_MODE_OF_EQUAL => '等于',
            self::DISCOUNT_MODE_OF_MUL => '倍数',
        ];

        return is_null($name) ? $data : $data[$name];
    }


    public static function getMultiType($name = null)
    {
        $data = [
            self::MULTI_TYPE_OF_ONLY => '不可叠加',
            self::MULTI_TYPE_OF_CAN_MULTI => '可叠加',
        ];

        return is_null($name) ? $data : $data[$name];
    }


    const STATUS_OF_TICKET_USED = 1;
    const STATUS_OF_TICKET_OK = 0;
    const STATUS_OF_TICKET_EXPIRED = 2;
    const STATUS_OF_TICKET_CANCEL = 3;

    const LENGTH_OF_TICKET_NO = 24;


    const ACTIVE_OF_OK = 1;
    const ACTIVE_OF_NO = 0;

    public static function validActive($active)
    {
        return (in_array($active, [
            self::ACTIVE_OF_OK,
            self::ACTIVE_OF_NO,
        ])) ? $active : self::ACTIVE_OF_OK;
    }
}

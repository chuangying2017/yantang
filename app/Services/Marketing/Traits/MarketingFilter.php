<?php

namespace App\Services\Marketing\Traits;

use App\Services\Marketing\MarketingProtocol;
use Carbon\Carbon;

trait MarketingFilter {

    public static function contentFilter($data, $coupon_type)
    {
        switch ($coupon_type) {
            case MarketingProtocol::TYPE_OF_COUPON:
            case MarketingProtocol::TYPE_OF_COUPON_CODE:
                $type = array_get($data, 'type', MarketingProtocol::DISCOUNT_TYPE_OF_CASH);
                $name = array_get($data, 'name', MarketingProtocol::COUPON_DEFAULT_NAME);

                return array_merge(array_only($data, ['content', 'detail']), compact('type', 'name'));
            case MarketingProtocol::TYPE_OF_CASH_BACK:
                $type = array_get($data, 'type', MarketingProtocol::DISCOUNT_TYPE_OF_CASH);

                return array_merge(array_only($data, ['content']), compact('type'));
            case MarketingProtocol::TYPE_OF_GIFT:
                return ['product_sku_id' => array_get($data, 'sku_id', 0)];
            default:
                return null;
        }
    }

    public static function limitFilter($data)
    {
        $quantity = array_get($data, 'quantity', MarketingProtocol::QUANTITY_NO_LIMIT);
        $amount_limit = array_get($data, 'amount', MarketingProtocol::AMOUNT_NO_LIMIT);
        $category_limit = isset($data['category']) ? array_to_string($data['category']) : MarketingProtocol::CATEGORY_NO_LIMIT;
        $product_limit = isset($data['product']) ? array_to_string($data['product']) : MarketingProtocol::PRODUCT_LIMIT;
        $multi_use = array_get($data, 'multi', MarketingProtocol::CAN_NOT_MULTI_USE);
        $effect_time = array_get($data, 'effect_time', Carbon::now());
        $expire_time = array_get($data, 'expire_time', MarketingProtocol::LIMIT_NO_EXPIRE_TIME);
        $roles = array_get($data, 'roles', null);
        $roles = $roles ? implode(',', $roles) : $roles;
        $quantity_per_user = array_get($data, 'quantity_per_user', 1);

        return compact('quantity', 'amount_limit', 'category_limit', 'product_limit', 'multi_use', 'effect_time', 'expire_time', 'roles', 'quantity_per_user');
    }

}

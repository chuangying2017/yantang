<?php namespace App\Services\Marketing\Items\Coupon;


use App\Services\Marketing\Exceptions\MarketingItemDistributeException;
use App\Services\Marketing\MarketingItemDistributor;
use App\Services\Marketing\MarketingProtocol;
use App\Services\Marketing\MarketingRepository;

class CouponDistributor extends MarketingItemDistributor {

    const RESOURCE_TYPE = MarketingProtocol::TYPE_OF_COUPON;

    protected function auth($coupon_id, $user_info)
    {
        try {
            if ($this->isAuth()) {
                return 1;
            }
            $user_id = array_get($user_info, 'id');

            $this->setResourceType(self::RESOURCE_TYPE);
            $coupon = MarketingRepository::queryFullCoupon($coupon_id);
            $can_take = $this->filter($coupon, $user_info);
            if ( ! $can_take) {
                return 0;
            }

            $this->isAuth(self::MARKETING_ITEM_CAN_DISTRIBUTE_TO_USER);

        } catch (\Exception $e) {
            throw $e;
        }

        return 1;
    }

    public function send($coupon_id, $user_info, $need_auth = true)
    {
        $user_id = array_get($user_info, 'id');

        if ( ! $need_auth) {
            $this->isAuth(self::MARKETING_ITEM_CAN_DISTRIBUTE_TO_USER);
        }

        if ( ! $this->isAuth()) {
            throw new MarketingItemDistributeException($this->getErrorMessage());
        }

        return $this->sendSucceed($coupon_id, $user_id);
    }

    protected function sendSucceed($coupon_id, $user_id)
    {
        $ticket = MarketingRepository::storeTicket($user_id, $coupon_id, self::RESOURCE_TYPE, true);
        MarketingRepository::decrementDiscountAmountLimit($coupon_id, self::RESOURCE_TYPE, 1);

        return $ticket;
    }
}

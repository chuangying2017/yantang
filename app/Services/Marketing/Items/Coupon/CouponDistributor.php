<?php namespace App\Services\Marketing\Items\Coupon;


use App\Services\Marketing\MarketingItemDistributor;
use App\Services\Marketing\MarketingRepository;

class CouponDistributor extends MarketingItemDistributor  {



    protected function auth($id, $user_id)
    {
        // TODO: Implement auth() method.
    }

    public function send($id, $user_id)
    {

    }


    protected function sendSucceed($coupon_id, $user_id)
    {
        $ticket = MarketingRepository::storeTicket($user_id, $coupon_id);
    }
}

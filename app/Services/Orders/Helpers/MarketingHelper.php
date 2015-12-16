<?php namespace App\Services\Orders\Helpers;

trait MarketingHelper {

    public static function coupon()
    {
        return app('App\Services\Marketing\Items\Coupon\UseCoupon');
    }

    public function useCoupon()
    {
        $this->setMarketUsing(app('App\Services\Marketing\Items\Coupon\UseCoupon'));
        return $this;
    }

    public function setMarketUsing($marketingUsing)
    {
        $this->marketingItemUsing = $marketingUsing;

        return $this;
    }

}

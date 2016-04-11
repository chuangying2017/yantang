<?php namespace App\Services\Marketing;

use Illuminate\Support\ServiceProvider;

class MarketingServiceProvider extends ServiceProvider {


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('marketing.distribute.coupon', function ($app) {
            return new Items\Coupon\CouponDistributor();
        });

        $this->app->bind('marketing.manager.coupon', function ($app) {
            return new Items\Coupon\CouponManager();
        });

        $this->app->bind('marketing.using.coupon', function ($app) {
            return new Items\Coupon\UseCoupon();
        });
    }
}

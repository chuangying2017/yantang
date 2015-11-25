<?php namespace App\Services\Marketing;

use App\Services\Marketing\Items\Coupon\CouponDistributor;
use App\Services\Marketing\Items\Coupon\CouponManager;
use App\Services\Marketing\Items\Coupon\UseCoupon;
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
            return new CouponDistributor();
        });

        $this->app->bind('marketing.manager.coupon', function ($app) {
            return new CouponManager();
        });

        $this->app->bind('marketing.using.coupon', function ($app) {
            return new UseCoupon();
        });
    }
}

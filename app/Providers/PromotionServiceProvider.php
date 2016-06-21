<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;

class PromotionServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Repositories\Store\StoreRepositoryContract::class,
            \App\Repositories\Store\StoreRepository::class
        );

        $this->app->bind(
            \App\Repositories\OrderTicket\OrderTicketRepositoryContract::class,
            \App\Repositories\OrderTicket\EloquentOrderTicketRepository::class
        );

        $this->app->bind(
            \App\Services\OrderTicket\OrderTicketManageContract::class,
            \App\Services\OrderTicket\OrderTicketManageService::class
        );

        $this->app->bind(
            \App\Repositories\Promotion\Campaign\CampaignRepositoryContract::class,
            \App\Repositories\Promotion\Campaign\EloquentCampaignRepository::class
        );

        $this->app->bind(
            \App\Repositories\Promotion\Coupon\CouponRepositoryContract::class,
            \App\Repositories\Promotion\Coupon\EloquentCouponRepository::class
        );

        $this->app->bind(
            \App\Repositories\Promotion\Rule\RuleRepositoryContract::class,
            \App\Repositories\Promotion\Rule\EloquentRuleRepository::class
        );
    }
}

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
            \App\Repositories\OrderTicket\OrderTicketStatementRepoContract::class,
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

        $this->app->bind(
            \App\Services\Promotion\Rule\RuleServiceContract::class,
            \App\Services\Promotion\Rule\RuleService::class
        );

        $this->app->bind(
            \App\Services\Promotion\Rule\Data\RuleDataContract::class,
            \App\Services\Promotion\Rule\Data\RuleData::class
        );

        $this->app->bind(
            \App\Services\Promotion\Support\PromotionAbleUserContract::class,
            \App\Repositories\Auth\User\EloquentUserRepository::class
        );


        $this->app->bind(
            \App\Repositories\Store\Statement\StoreStatementRepositoryContract::class,
            \App\Repositories\Store\Statement\StoreStatementRepository::class
        );

        $this->app->bind(
            \App\Services\Store\Statement\StoreStatementServiceContract::class,
            \App\Services\Store\Statement\StoreStatementService::class
        );


    }
}

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
            \App\Repositories\Promotion\Giftcard\GiftcardRepositoryContract::class,
            \App\Repositories\Promotion\Giftcard\EloquentGiftcardRepository::class
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
            \App\Services\Promotion\Rule\SpecifyRuleContract::class,
            \App\Services\Promotion\Rule\SpecifyRuleService::class
        );

        $this->app->bind(
            \App\Services\Promotion\Support\PromotionAbleUserContract::class,
            \App\Repositories\Auth\User\EloquentUserRepository::class
        );

        $this->app->bind(
            \App\Repositories\Promotion\TicketRepositoryContract::class,
            \App\Repositories\Promotion\EloquentTicketRepository::class
        );


    }
}

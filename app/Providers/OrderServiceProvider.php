<?php

namespace App\Providers;


use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider {


    /**
     * List of only Local Environment Facade Aliases
     * @var array
     */
    protected $facadeAliases = [

    ];


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
            \App\Repositories\Cart\CartRepositoryContract::class,
            \App\Repositories\Cart\EloquentCartRepository::class
        );

        $this->app->bind(
            \App\Repositories\Order\Sku\OrderSkuRepositoryContract::class,
            \App\Repositories\Order\Sku\EloquentOrderSkuRepository::class
        );

        $this->app->bind(
            \App\Services\Order\OrderManageContract::class,
            \App\Services\Order\OrderManageService::class
        );

        $this->app->bind(
            \App\Services\Order\Checkout\OrderCheckoutContract::class,
            \App\Services\Order\Checkout\OrderCheckoutService::class
        );

        $this->app->bind(
            \App\Repositories\Order\Promotion\OrderPromotionRepositoryContract::class,
            \App\Repositories\Order\Promotion\OrderPromotionRepository::class
        );

        $this->app->bind(
            \App\Repositories\Order\Refund\RefundOrderRepositoryContract::class,
            \App\Repositories\Order\RefundClientOrderRepository::class
        );

        $this->app->bind(
            \App\Repositories\Order\OrderCounterRepositoryContract::class,
            \App\Repositories\Order\ClientOrderRepository::class
        );


        $this->app->bind(
            \App\Repositories\Member\InterfaceFile\MemberRepositoryContract::class,
            \App\Repositories\Member\ReposFile\MemberLimitRepository::class
        );
    }


    /**
     * Load additional Aliases
     */
    public function registerFacadeAliases()
    {
        $loader = AliasLoader::getInstance();
        foreach ($this->facadeAliases as $alias => $facade) {
            $loader->alias($alias, $facade);
        }
    }


}

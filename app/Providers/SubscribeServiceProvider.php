<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Subscribe\PreorderService;
use App\Services\Subscribe\PreorderProductService;


class SubscribeServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->registerPreorderService();
        $this->registerPreorderProductService();
//        $this->registerFacade();
        $this->registerBindings();
    }

    private function registerPreorderService()
    {
        $this->app->bind('PreorderService', function ($app) {
            return new PreorderService($app);
        });
    }

    private function registerPreorderProductService()
    {
        $this->app->bind('PreorderProductService', function ($app) {
            return new PreorderProductService($app);
        });
    }

    public function registerFacade()
    {
        $this->app->booting(function () {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('PreorderService', \App\Services\Subscribe\Facades\PreorderService::class);
        });
    }

    public function registerBindings()
    {
        $this->app->bind(
            \App\Repositories\Subscribe\Staff\StaffRepositoryContract::class,
            \App\Repositories\Subscribe\Staff\EloquentStaffRepository::class
        );

        $this->app->bind(
            \App\Repositories\Subscribe\Station\StationRepositoryContract::class,
            \App\Repositories\Subscribe\Station\EloquentStationRepository::class
        );

        $this->app->bind(
            \App\Repositories\Subscribe\Address\AddressRepositoryContract::class,
            \App\Repositories\Subscribe\Address\EloquentAddressRepository::class
        );

        $this->app->bind(
            \App\Repositories\Subscribe\Preorder\PreorderRepositoryContract::class,
            \App\Repositories\Subscribe\Preorder\EloquentPreorderRepository::class
        );

        $this->app->bind(
            \App\Repositories\Subscribe\PreorderProduct\PreorderProductRepositoryContract::class,
            \App\Repositories\Subscribe\PreorderProduct\EloquentPreorderProductRepository::class
        );

        $this->app->bind(
            \App\Repositories\Subscribe\PreorderProductSku\PreorderProductSkuRepositoryContract::class,
            \App\Repositories\Subscribe\PreorderProductSku\EloquentPreorderProductSkuRepository::class
        );

        $this->app->bind(
            \App\Repositories\Subscribe\StaffPreorder\StaffPreorderRepositoryContract::class,
            \App\Repositories\Subscribe\StaffPreorder\EloquentStaffPreorderRepository::class
        );
    }

}

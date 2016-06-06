<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Subscribe\PreorderService;
use App\Services\Subscribe\PreorderProductService;
use App\Services\Subscribe\StaffService;


class SubscribeServiceProvider extends ServiceProvider
{


    public function register()
    {
        $this->registerBindings();
        $this->registerFacade();
    }


    public function registerFacade()
    {
        $this->app->singleton('PreorderService', function ($app) {
            return $app->make(PreorderService::class);
        });

        $this->app->singleton('PreorderProductService', function ($app) {
            return $app->make(PreorderProductService::class);
        });

        $this->app->singleton('StaffService', function ($app) {
            return $app->make(StaffService::class);
        });

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('PreorderService', \App\Services\Subscribe\Facades\PreorderService::class);
        $loader->alias('StaffService', \App\Services\Subscribe\Facades\StaffService::class);
        $loader->alias('PreorderProductService', \App\Services\Subscribe\Facades\PreorderProductService::class);
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

        $this->app->bind(
            \App\Repositories\Subscribe\StaffWeekly\StaffWeeklyRepositoryContract::class,
            \App\Repositories\Subscribe\StaffWeekly\EloquentStaffWeeklyRepository::class
        );
    }

}

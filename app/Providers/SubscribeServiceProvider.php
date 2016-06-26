<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Subscribe\PreorderService;
use App\Services\Subscribe\PreorderProductService;
use App\Services\Subscribe\StaffService;
use App\Services\Subscribe\StationService;
use App\Services\Subscribe\StatementsService;

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

        $this->app->singleton('StationService', function ($app) {
            return $app->make(StationService::class);
        });

        $this->app->singleton('StatementsService', function ($app) {
            return $app->make(StatementsService::class);
        });

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('PreorderService', \App\Services\Subscribe\Facades\PreorderService::class);
        $loader->alias('StaffService', \App\Services\Subscribe\Facades\StaffService::class);
        $loader->alias('StationService', \App\Services\Subscribe\Facades\StationService::class);
        $loader->alias('PreorderProductService', \App\Services\Subscribe\Facades\PreorderProductService::class);
        $loader->alias('StatementsService', \App\Services\Subscribe\Facades\StatementsService::class);
    }

    public function registerBindings()
    {
        $this->app->bind(
            \App\Repositories\Station\Staff\StaffRepositoryContract::class,
            \App\Repositories\Station\Staff\EloquentStaffRepository::class
        );

        $this->app->bind(
            \App\Repositories\Station\StationRepositoryContract::class,
            \App\Repositories\Station\EloquentStationRepository::class
        );

        $this->app->bind(
            \App\Repositories\Preorder\PreorderRepositoryContract::class,
            \App\Repositories\Preorder\EloquentPreorderRepository::class
        );


    }

}

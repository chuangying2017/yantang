<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;


class SubscribeServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(
            \App\Repositories\Backend\Staff\StaffRepositoryContract::class,
            \App\Repositories\Backend\Staff\EloquentStaffRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\Station\StationRepositoryContract::class,
            \App\Repositories\Backend\Station\EloquentStationRepository::class
        );
    }

}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SubscribeServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->registerBindings();
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
            \App\Repositories\Residence\ResidenceRepositoryContract::class,
            \App\Repositories\Residence\EloquentResidenceRepository::class
        );

        $this->app->bind(
            \App\Repositories\Preorder\PreorderRepositoryContract::class,
            \App\Repositories\Preorder\EloquentPreorderRepository::class
        );

        $this->app->bind(
            \App\Services\Preorder\PreorderAssignServiceContact::class,
            \App\Services\Preorder\PreorderAssignService::class
        );

        $this->app->bind(
            \App\Repositories\Preorder\Assign\PreorderAssignRepositoryContract::class,
            \App\Repositories\Preorder\Assign\PreorderAssignRepository::class
        );

        $this->app->bind(
            \App\Repositories\Station\StationPreorderRepositoryContract::class,
            \App\Repositories\Preorder\EloquentPreorderRepository::class
        );

        $this->app->bind(
            \App\Repositories\Station\District\DistrictRepositoryContract::class,
            \App\Repositories\Station\District\DistrictRepository::class
        );

        $this->app->bind(
            \App\Services\Preorder\PreorderManageServiceContract::class,
            \App\Services\Preorder\PreorderManagerService::class
        );

        $this->app->bind(
            \App\Repositories\Preorder\Product\PreorderSkusRepositoryContract::class,
            \App\Repositories\Preorder\Product\PreorderSkusRepository::class
        );

        $this->app->bind(
            \App\Repositories\Preorder\Deliver\PreorderDeliverRepositoryContract::class,
            \App\Repositories\Preorder\Deliver\PreorderDeliverRepository::class
        );

        $this->app->bind(
            'collect_order',
            \App\Models\Collect\CollectOrder::class
        );

    }

}

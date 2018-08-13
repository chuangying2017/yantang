<?php

namespace App\Providers;

use App\Api\V1\Controllers\Admin\Integral\CompanyController;
use App\Api\V1\Controllers\Admin\Integral\FreedomController;
use App\Api\V1\Controllers\Integral\FetchIntegralController;
use App\Repositories\Integral\Address\UserAddress;
use App\Repositories\Integral\Card\OperationMode;
use App\Repositories\Integral\Company\EloquentCompanyRepositories;
use App\Repositories\Integral\Exchange\ExchangeOperation;
use App\Repositories\Integral\OrderHandle\OrderIntegralInterface;
use App\Repositories\Integral\OrderHandle\OrderIntegralRepository;
use App\Repositories\Integral\ShareCarriageWheel\ShareAccessRepositories;
use App\Repositories\Integral\Supervisor\Supervisor;
use App\Services\Integral\Category\Category;
use App\Services\Integral\Category\IntegralCategoryMangers;
use App\Services\Integral\Product\ProductInerface;
use App\Services\Integral\Product\ProductManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class IntegralCateGoryServiceProvider extends ServiceProvider
{
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
        //
        $this->app->bind(IntegralCategoryMangers::class, Category::class);
        $this->app->bind(ProductInerface::class,ProductManager::class);
        $this->app->bind(OrderIntegralInterface::class,OrderIntegralRepository::class);
        $this->app->bind(Supervisor::class,UserAddress::class);
        $this->app->when(CompanyController::class)->needs(Supervisor::class)->give(EloquentCompanyRepositories::class);
        $this->app->when(FreedomController::class)->needs(Supervisor::class)->give(OperationMode::class);
        $this->app->bind(ShareAccessRepositories::class,ExchangeOperation::class);
        $this->app->when(FetchIntegralController::class)->needs(Supervisor::class)->give(OperationMode::class);
    }
}

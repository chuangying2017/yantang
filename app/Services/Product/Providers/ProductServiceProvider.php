<?php namespace App\Services\Product\Providers;

use App\Models\Product\Product;
use App\Repositories\Search\Item\ProductSearchRepository;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider {

    public function boot()
    {

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->observes();

        $this->app->bind(
            \App\Repositories\Product\ProductRepositoryContract::class,
            \App\Repositories\Product\EloquentProductRepository::class
        );

        $this->app->bind(
            \App\Repositories\Product\ProductSubscribeRepositoryContract::class,
            \App\Repositories\Product\EloquentProductRepository::class
        );

        $this->app->bind(
            \App\Repositories\Product\Sku\ProductSkuRepositoryContract::class,
            \App\Repositories\Product\Sku\EloquentProductSkuRepository::class
        );

        $this->app->bind(
            \App\Repositories\Product\Sku\ProductSkuStockRepositoryContract::class,
            \App\Repositories\Product\Sku\EloquentProductSkuRepository::class
        );

        $this->app->bind(
            \App\Repositories\Product\Attribute\AttributeRepositoryContract::class,
            \App\Repositories\Product\Attribute\EloquentAttributeRepository::class
        );

        $this->app->bind(
            \App\Repositories\Product\Attribute\AttributeValueRepositoryContract::class,
            \App\Repositories\Product\Attribute\EloquentAttributeValueRepository::class
        );

        $this->app->bind(
            \App\Repositories\Product\Brand\BrandRepositoryContract::class,
            \App\Repositories\Product\Brand\EloquentBrandRepository::class
        );

        $this->app->bind(
            \App\Repositories\Product\Group\GroupRepositoryContract::class,
            \App\Repositories\Product\Group\EloquentGroupRepository::class
        );

        $this->app->bind(
            \App\Repositories\Category\CategoryRepositoryContract::class,
            \App\Repositories\Category\EloquentCategoryRepository::class
        );

    }

    protected function setAlias()
    {
//        $loader = AliasLoader::getInstance();
//        $loader->alias('ProductSearch', ProductSearch::class);
    }

    protected function observes()
    {
        Product::created(function ($product) {
            $this->app->make(ProductSearchRepository::class)->create($product);
        });

        Product::restored(function ($product) {
            $this->app->make(ProductSearchRepository::class)->create($product);
        });

        Product::updated(function ($product) {
            $this->app->make(ProductSearchRepository::class)->update($product);
        });

        Product::deleted(function ($product) {
            $this->app->make(ProductSearchRepository::class)->delete($product);
        });
    }

}

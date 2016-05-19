<?php namespace App\Services\Product\Search;


use App\Services\Product\ProductSkuRepository;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider {

    public function boot()
    {
        $this->setAlias();
        #todo 监听时间更新搜索索引
//        Product::saved(function ($product) {
//            app()->make('product_search')->add($product);
//        });
//
//        Product::restored(function ($product) {
//            app()->make('product_search')->add($product);
//        });
//
//        Product::deleted(function ($product) {
//            app()->make('product_search')->delete($product);
//        });


    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('product_search', function ($app) {
            return new ProductSearchService();
        });

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

}

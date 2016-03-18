<?php namespace App\Services\Product\Search;

use App\Models\Product;
use App\Services\Product\Search\Facades\ProductSearch;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider {

    public function boot()
    {
        $this->app->singleton('product_search', function ($app) {
            return new ProductSearchService();
        });

        Product::saved(function ($product) {

            app()->make('product_search')->add($product);

        });

        Product::deleted(function ($product) {
            app()->make('product_search')->delete($product);
        });

        $loader = AliasLoader::getInstance();
        $loader->alias('ProductSearch', ProductSearch::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

}

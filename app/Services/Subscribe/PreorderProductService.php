<?php namespace App\Services\Subscribe;

use App\Models\Subscribe\PreorderProductSku;

/**
 * Class Access
 * @package App\Services\Access
 */
class PreorderProductService
{

    /**
     * Laravel application
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;

    /**
     * Create a new confide instance.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    public function create($input)
    {
        $preorder_product = $this->app->make('App\Repositories\Subscribe\PreorderProduct\PreorderProductRepositoryContract');
        $product_input = array_only(['preorder_id', 'weekday', 'daytime'], $input);
        $skus = array_get($input, 'sku');
        $preorder_product = $preorder_product->create($product_input);
        $preorder_product_sku = $this->app->make('App\Repositories\Subscribe\PreorderProductSku\PreorderProductSkuRepositoryContract');
        foreach ($skus as $sku_value) {
            $preorder_product_sku[] = $preorder_product_sku->create($sku_value);
        }
        $preorder_product->preorder_product_sku = $preorder_product_sku;
        return $preorder_product;
    }

}

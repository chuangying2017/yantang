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

    public function operation($input, $id)
    {
        $preorder_product = $this->app->make('App\Repositories\Subscribe\PreorderProduct\PreorderProductRepositoryContract');
        $skus = json_decode(array_get($input, 'sku'), true);
        unset($input['sku']);

        if (empty($id)) {
            $preorder_product = $preorder_product->create($input);
        } else {
            $preorder_product = $preorder_product->update($input, $id);
        }

        foreach ($skus as $value) {
            if (!empty($id)) {
                $product_sku = $this->app->make('App\Repositories\Subscribe\PreorderProductSku\PreorderProductSkuRepositoryContract');
                $product_sku->delete($preorder_product->id);
            }
            $value['pre_product_id'] = $preorder_product->id;
            $preorder_product_sku[] = new PreorderProductSku($value);
        }
        $sku = $preorder_product->preorderProductSku()->saveMany($preorder_product_sku);

        $preorder_product->preorder_product_sku = $sku;

        return $preorder_product;
    }

}

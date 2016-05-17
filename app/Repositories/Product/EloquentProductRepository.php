<?php namespace App\Repositories\Product;

use App\Models\Product\Product;
use App\Repositories\Product\Editor\AddInfo;
use App\Repositories\Product\Editor\AddMeta;
use App\Repositories\Product\Editor\AttachProductSku;
use App\Repositories\Product\Editor\CheckAttributes;
use App\Repositories\Product\Editor\FillProduct;
use App\Repositories\Product\Editor\SetStatus;
use App\Repositories\Product\Editor\TransPrice;
use App\Repositories\Product\Sku\ProductSkuRepositoryContract;

class EloquentProductRepository implements ProductRepositoryContract, ProductSubscribeRepositoryContract {

    /**
     * @var ProductSkuRepositoryContract
     */
    private $productSkuRepository;
    /**
     * @var
     */
    private $app;

    /**
     * EloquentProductRepository constructor.
     * @param ProductSkuRepositoryContract $productSkuRepository
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct(\App $app, ProductSkuRepositoryContract $productSkuRepository)
    {
        $this->productSkuRepository = $productSkuRepository;
        $this->app = $app;
    }

    public function createProduct($product_data)
    {
        $handler = $this->getCreateProductHandler();

        $result = $handler->handle($product_data, new Product());

        $product = $result['product'];

        return $product;
    }

    protected function getCreateProductHandler()
    {
        $config = [
            TransPrice::class,
            SetStatus::class,
            CheckAttributes::class,
            FillProduct::class,
            AddInfo::class,
            AddMeta::class,
            AttachProductSku::class,
        ];

        $starter = null;
        $previous_handler = null;

        foreach ($config as $handler_name) {
            $current_handler = $this->app->make($handler_name);
            if (is_null($starter)) {
                $starter = $current_handler;
            } else {
                $previous_handler->editWith($current_handler);
            }
            $previous_handler = $current_handler;
        }

        return $starter;
    }

    public function updateProduct($product_id, $product_data)
    {
        // TODO: Implement updateProduct() method.
    }

    public function getProduct($product_id)
    {
        // TODO: Implement getProduct() method.
    }

    public function getAllProducts($order_by = 'created_at', $sort = 'desc', $status, $brand = null, $cat = null)
    {
        // TODO: Implement getAllProducts() method.
    }

    public function getProductsPaginated($order_by = 'created_at', $sort = 'desc', $status, $brand = null, $cat = null, $per_page = ProductProtocol::PRODUCT_PER_PAGE)
    {
        // TODO: Implement getProductsPaginated() method.
    }

    public function deleteProduct($product_id)
    {
        // TODO: Implement deleteProduct() method.
    }

    public function search($keyword, $options = [])
    {
        // TODO: Implement search() method.
    }

    public function getAllSubscribedProducts()
    {
        // TODO: Implement getAllSubscribedProducts() method.
    }
}

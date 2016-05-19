<?php namespace App\Repositories\Product;

use App\Models\Product\Product;
use App\Repositories\Category\CategoryProtocol;
use App\Repositories\Product\Editor\AttachInfo;
use App\Repositories\Product\Editor\AttachMeta;
use App\Repositories\Product\Editor\AttachProductSku;
use App\Repositories\Product\Editor\RelateProduct;
use App\Repositories\Product\Editor\SetAttributes;
use App\Repositories\Product\Editor\FillProduct;
use App\Repositories\Product\Editor\SetProductSku;
use App\Repositories\Product\Editor\SetStatus;
use App\Repositories\Product\Editor\SetPrice;
use App\Repositories\Product\Editor\UpdateInfo;
use App\Repositories\Product\Editor\UpdateMeta;
use App\Repositories\Product\Editor\UpdateProductSku;
use App\Repositories\Product\Sku\ProductSkuRepositoryContract;
use Carbon\Carbon;

class EloquentProductRepository implements ProductRepositoryContract, ProductSubscribeRepositoryContract {

    /**
     * @var ProductSkuRepositoryContract
     */
    private $productSkuRepository;

    /**
     * EloquentProductRepository constructor.
     * @param ProductSkuRepositoryContract $productSkuRepository
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct(ProductSkuRepositoryContract $productSkuRepository)
    {
        $this->productSkuRepository = $productSkuRepository;
    }

    public function createProduct($product_data)
    {
        $handler = $this->getCreateProductHandler();

        $result = \DB::transaction(function () use ($handler, $product_data) {
            return $handler->handle($product_data, new Product());
        });

        $product = $result['product'];

        return $product;
    }

    private function getCreateProductHandler()
    {
        $config = [
            SetProductSku::class,
            SetPrice::class,
            SetStatus::class,
            SetAttributes::class,
            FillProduct::class,
            AttachInfo::class,
            AttachMeta::class,
            RelateProduct::class,
            AttachProductSku::class,
        ];

        return $this->getProductHandler($config);
    }

    private function getUpdateProductHandler()
    {
        $config = [
            SetProductSku::class,
            SetPrice::class,
            SetStatus::class,
            SetAttributes::class,
            FillProduct::class,
            UpdateInfo::class,
            UpdateMeta::class,
            RelateProduct::class,
            UpdateProductSku::class,
        ];

        return $this->getProductHandler($config);
    }

    private function getProductHandler($config)
    {
        $starter = null;
        $previous_handler = null;

        foreach ($config as $handler_name) {
            $current_handler = \App::make($handler_name);
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
        $handler = $this->getUpdateProductHandler();

        $result = \DB::transaction(function () use ($handler, $product_data) {
            return $handler->handle($product_data, new Product());
        });

        $product = $result['product'];

        return $product;
    }

    public function getProduct($product_id, $with_detail = true)
    {
        $product = Product::find($product_id);
        if ($with_detail) {
            $product = $product->load('skus', 'cats', 'brand', 'meta', 'info');
        }
        return $product;
    }

    public function getAllProducts($brand = null, $cat = null, $group = null, $order_by = 'created_at', $sort = 'desc', $status = ProductProtocol::VAR_PRODUCT_STATUS_UP)
    {

        return $this->queryProducts($order_by, $sort, $status, $brand, merge_array($group, $cat));
    }


    public function getProductsPaginated($brand = null, $cat = null, $group = null, $order_by = 'created_at', $sort = 'desc', $status = ProductProtocol::VAR_PRODUCT_STATUS_UP, $per_page = ProductProtocol::PRODUCT_PER_PAGE)
    {
        return $this->queryProducts($order_by, $sort, $status, $brand, merge_array($group, $cat), null, $per_page);
    }

    protected function queryProducts($order_by = 'created_at', $sort = 'desc', $status = null, $brand = null, $cats = null, $type = null, $per_page = null, $with_time = true)
    {
        $query = Product::query();

        if ($with_time) {
            $query = $query->where(function ($query) {
                $now = Carbon::now();
                $query->where('open_time', '<=', $now);
                $query->where('end_time', '>=', $now);
            });
        }

        if ($brand) {
            $query = $query->where('brand_id', $brand);
        }

        if ($cats) {
            $query = $query->whereHas('cats', function ($query) use ($cats) {
                $query->whereIn('id', to_array($cats));
            });
        }

        if ($type) {
            $query = $query->where('type', $type);
        }

        if ($status) {
            $query = $query->where('status', $status);
        }

        $query = $query->orderBy($order_by, $sort);

        if ($per_page) {
            return $query->paginate($per_page);
        }

        return $query->get();
    }

    public function deleteProduct($product_id)
    {
        $product = $this->getProduct($product_id, false);
        return \DB::transaction(function () use ($product) {
            $product->info()->delete();
            $product->meta()->delete();
            $product->cats()->detach();
            $this->productSkuRepository->deleteSkusOfProduct($product['id']);
            $product->delete();
            return 1;
        });
    }

    public function search($keyword, $options = [])
    {
        // TODO: Implement search() method.
    }

    public function getAllSubscribedProducts($status = ProductProtocol::VAR_PRODUCT_STATUS_UP, $with_time = true)
    {
        return $this->queryProducts('created_at', 'asc', $status, null, CategoryProtocol::ID_OF_SUBSCRIBE_GROUP, null, null, $with_time);
    }

    public function setProductsStopSubscribe($product_id)
    {
        return Product::whereIn('id', to_array($product_id))->update(['end_time' => Carbon::now()]);
    }

    public function setProductsStartSubscribe($product_id)
    {
        return Product::whereIn('id', to_array($product_id))->update(['end_time' => Carbon::now()->addYears(10)]);
    }

}

<?php namespace App\Repositories\Product;

use App\Models\Product\Product;
use App\Repositories\Product\Editor\AttachInfo;
use App\Repositories\Product\Editor\AttachMeta;
use App\Repositories\Product\Editor\AttachProductSku;
use App\Repositories\Product\Editor\RelateProduct;
use App\Repositories\Product\Editor\SetAttributes;
use App\Repositories\Product\Editor\FillProduct;
use App\Repositories\Product\Editor\SetProductSku;
use App\Repositories\Product\Editor\SetStatus;
use App\Repositories\Product\Editor\SetPrice;
use App\Repositories\Product\Editor\UpdateImages;
use App\Repositories\Product\Editor\UpdateInfo;
use App\Repositories\Product\Editor\UpdateMeta;
use App\Repositories\Product\Editor\UpdateProductSku;
use App\Repositories\Product\Sku\ProductSkuRepositoryContract;
use App\Repositories\Search\Item\ProductSearchRepository;
use Carbon\Carbon;

class EloquentProductRepository implements ProductRepositoryContract {

    /**
     * @var ProductSkuRepositoryContract
     */
    private $productSkuRepository;
    /**
     * @var ProductSearchRepository
     */


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
            UpdateImages::class,
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
            UpdateImages::class,
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

        $result = \DB::transaction(function () use ($handler, $product_data, $product_id) {
            return $handler->handle($product_data, $this->getProduct($product_id));
        });

        $product = $result['product'];

        return $product;
    }

    public function getProduct($product_id, $with_detail = true)
    {
        $product = Product::query()->findOrFail($product_id);
        if ($with_detail) {
            $product = $product->load('skus', 'cats', 'brand', 'groups', 'meta', 'info', 'images');
        }
        return $product;
    }

    public function getAllProducts($brand = null, $cat = null, $group = null, $type = null, $status = ProductProtocol::VAR_PRODUCT_STATUS_UP, $order_by = 'created_at', $sort = 'desc')
    {
        return $this->queryProducts($order_by, $sort, $status, $brand, merge_array($group, $cat), $type);
    }


    public function getProductsPaginated($brand = null, $cat = null, $group = null, $type = null, $status = ProductProtocol::VAR_PRODUCT_STATUS_UP, $order_by = 'created_at', $sort = 'desc', $per_page = ProductProtocol::PRODUCT_PER_PAGE)
    {
        return $this->queryProducts($order_by, $sort, $status, $brand, merge_array($group, $cat), $type, $per_page);
    }

    protected function queryProducts($order_by = 'created_at', $sort = 'desc', $status = null, $brand = null, $cats = null, $type = null, $per_page = null, $with_time = false)
    {
        $query = Product::with('meta');

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
            $query = $query->join('product_category', function ($join) use ($cats) {
                $join->whereIn('cat_id', to_array($cats));
            });
        }


        if ($type) {
            $query = $query->where('type', $type);
        }

        if ($status) {
            $query->where('status', $status);
        }



        $query->orderBy('priority', 'desc');
        if (!is_null($order_by)) {
            $query->orderBy($order_by, $sort);
        }


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
        return (new ProductSearchRepository($this))->get($keyword);
    }


    public function updateProductAsUp($product_id)
    {
        $product = $this->getProduct($product_id, false);

        if ($product->status == ProductProtocol::VAR_PRODUCT_STATUS_UP) {
            return $product;
        }

        $product->status = ProductProtocol::VAR_PRODUCT_STATUS_UP;
        $product->save();

        return $product;
    }

    public function updateProductAsDown($product_id)
    {
        $product = $this->getProduct($product_id, false);

        if ($product->status == ProductProtocol::VAR_PRODUCT_STATUS_DOWN) {
            return $product;
        }

        $product->status = ProductProtocol::VAR_PRODUCT_STATUS_DOWN;
        $product->save();

        return $product;
    }
}

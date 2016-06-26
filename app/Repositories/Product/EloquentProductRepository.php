<?php namespace App\Repositories\Product;

use App\Models\Product\Brand;
use App\Models\Product\Product;
use App\Models\Product\ProductSku;
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
use App\Repositories\Product\Sku\ProductMixRepositoryContract;
use App\Repositories\Product\Sku\ProductSkuRepositoryContract;
use App\Repositories\Search\Item\ProductSearchRepository;
use Carbon\Carbon;
use EasyWeChat\User\Group;
use Illuminate\Database\Eloquent\Collection;

class EloquentProductRepository implements ProductRepositoryContract, ProductSubscribeRepositoryContract, ProductIdListContract {

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

        $result = \DB::transaction(function () use ($handler, $product_data, $product_id) {
            return $handler->handle($product_data, $this->getProduct($product_id));
        });

        $product = $result['product'];

        return $product;
    }

    public function getProduct($product_id, $with_detail = true)
    {
        $product = Product::findOrFail($product_id);
        if ($with_detail) {
            $product = $product->load('skus', 'cats', 'brand', 'groups', 'meta', 'info');
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

    protected function queryProducts($order_by = 'created_at', $sort = 'desc', $status = null, $brand = null, $cats = null, $type = null, $per_page = null, $with_time = false)
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
            $query = $query->join('product_category', function ($join) use ($cats) {
                $join->whereIn('cat_id', to_array($cats));
            });
        }


        if ($type) {
            $query = $query->where('type', $type);
        }

        if ($status) {
            $query = $query->where('status', $status);
        }

        $query = $query->orderBy('priority', 'desc')->orderBy($order_by, $sort);

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

    public function getAllSubscribedProducts($status = ProductProtocol::VAR_PRODUCT_STATUS_UP, $with_time = true, $expend = true)
    {
        $products = $this->queryProducts('created_at', 'asc', $status, null, CategoryProtocol::ID_OF_SUBSCRIBE_GROUP, null, null, $with_time);
        $products->load('skus');

        if ($expend) {
            $skus = null;
            foreach ($products as $product) {
                if (is_null($skus)) {
                    $skus = $product->skus;
                } else {
                    $skus->merge($product->skus);
                }
            }
            return $skus ? $skus : new Collection();
        }

        return $products;
    }

    public function setProductsStopSubscribe($product_id)
    {
        return Product::whereIn('id', to_array($product_id))->update(['end_time' => Carbon::now()]);
    }

    public function setProductsStartSubscribe($product_id)
    {
        return Product::whereIn('id', to_array($product_id))->update(['end_time' => Carbon::now()->addYears(10)]);
    }

    public function listsOfGroup($group_id)
    {
        return \DB::table('product_category')->where('cat_id', $group_id)->pluck('product_id');
    }

    public function listsOfCategory($cat_id)
    {
        return \DB::table('product_category')->where('cat_id', $cat_id)->pluck('product_id');
    }

    public function listsOfBrand($brand_id)
    {
        return Product::where('brand_id', $brand_id)->pluck('id');
    }


}

<?php

namespace App\Api\V1\Controllers\Admin\Product;

use App\Api\V1\Transformers\Admin\Product\ProductSkuTransformer;
use App\Api\V1\Transformers\Admin\Product\ProductTransformer;
use App\Repositories\Product\Sku\ProductMixRepositoryContract;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductMixController extends Controller {

    /**
     * @var ProductMixRepositoryContract
     */
    private $productMixRepo;

    /**
     * ProductMixController constructor.
     * @param ProductMixRepositoryContract $productMixRepo
     */
    public function __construct(ProductMixRepositoryContract $productMixRepo)
    {
        $this->productMixRepo = $productMixRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function skus()
    {
        $skus = $this->productMixRepo->getAllMixAbleProductSku();

        return $this->response->collection($skus, new ProductSkuTransformer());
    }

    public function products()
    {
        $products = $this->productMixRepo->getMixProducts();

        return $this->response->collection($products, new ProductTransformer());
    }


}

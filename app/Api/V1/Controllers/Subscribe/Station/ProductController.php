<?php

namespace App\Api\V1\Controllers\Subscribe\Station;

use App\Api\V1\Transformers\Subscribe\ProductSkuTransformer;
use App\Repositories\Product\ProductSubscribeRepositoryContract;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductController extends Controller {

    /**
     * @var ProductSubscribeRepositoryContract
     */
    private $productSubscribeRepositoryContract;

    /**
     * ProductController constructor.
     * @param ProductSubscribeRepositoryContract $productSubscribeRepositoryContract
     */
    public function __construct(ProductSubscribeRepositoryContract $productSubscribeRepositoryContract)
    {
        $this->productSubscribeRepositoryContract = $productSubscribeRepositoryContract;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product_skus = $this->productSubscribeRepositoryContract->getAllSubscribedProducts();

        return $this->response->collection($product_skus, new ProductSkuTransformer());
    }

}

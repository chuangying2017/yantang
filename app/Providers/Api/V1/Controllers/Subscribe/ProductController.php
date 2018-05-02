<?php
namespace App\Api\V1\Controllers\Subscribe;

use App\Api\V1\Transformers\Subscribe\ProductSkuTransformer;
use App\Repositories\Product\Sku\SubscribeSkuRepositoryContract;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductController extends Controller {

    /**
     * @var SubscribeSkuRepositoryContract
     */
    private $skuRepo;

    /**
     * ProductController constructor.
     * @param SubscribeSkuRepositoryContract $productSubscribeRepositoryContract
     */
    public function __construct(SubscribeSkuRepositoryContract $skuRepo)
    {
        $this->skuRepo = $skuRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->skuRepo->getAllSubscribedProducts();
        return $this->response->collection($products, new ProductSkuTransformer());
    }

}


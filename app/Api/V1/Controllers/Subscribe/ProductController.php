<?php
namespace App\Api\V1\Controllers\Subscribe;

use App\Api\V1\Transformers\Subscribe\ProductSkuTransformer;
use App\Models\Category;
use App\Repositories\Product\Sku\SubscribeSkuRepositoryContract;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;


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

        if(!$products=Cache::get('subscribe:product:data')){
            Cache::put('subscribe:product:data', $products=$this->skuRepo->getAllSubscribedProducts());
        }

        //$products['category'] = 1;

        //dd($products);

        return $this->response->collection($products, new ProductSkuTransformer());
    }
    /*    public function index()
    {
        $products = $this->skuRepo->getAllSubscribedProducts();

        //$products['category'] = 1;

        //dd($products);

        return $this->response->collection($products, new ProductSkuTransformer());
    }*/

}


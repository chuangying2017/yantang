<?php

namespace App\Api\V1\Controllers\Mall;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Transformers\Mall\ProductTransformer;
use App\Repositories\Product\ProductProtocol;
use App\Repositories\Product\ProductRepositoryContract;
use Illuminate\Http\Request;

use App\Http\Requests;

class ProductController extends Controller {

    /**
     * @var ProductRepositoryContract
     */
    private $productRepo;

    /**
     * ProductController constructor.
     * @param ProductRepositoryContract $productRepo
     */
    public function __construct(ProductRepositoryContract $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $brand = $request->input('brand');
        $cat = $request->input('cat');
        $group = $request->input('group');

        $keyword = $request->input('keyword');
        $status = ProductProtocol::VAR_PRODUCT_STATUS_UP;

        if ($keyword) {
            $products = $this->productRepo->search($keyword, compact('brand', 'cat', 'group', 'status'));
        } else {
            $products = $this->productRepo->getProductsPaginated($brand, $cat, $group, ProductProtocol::TYPE_OF_ENTITY);
        }

        return $this->response->paginator($products, new ProductTransformer());
    }
/*    public function index(Request $request)
    {
        $brand = $request->input('brand');
        $cat = $request->input('cat');
        $group = $request->input('group');

        $keyword = $request->input('keyword');
        $status = ProductProtocol::VAR_PRODUCT_STATUS_UP;

        if ($keyword) {
            $products = $this->productRepo->search($keyword, compact('brand', 'cat', 'group', 'status'));
        } else {
            $products = $this->productRepo->getProductsPaginated($brand, $cat, $group, ProductProtocol::TYPE_OF_ENTITY);
        }

        return $this->response->paginator($products, new ProductTransformer());
    }*/


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->productRepo->getProduct($id);

        return $this->response->item($product, new ProductTransformer());
    }


}

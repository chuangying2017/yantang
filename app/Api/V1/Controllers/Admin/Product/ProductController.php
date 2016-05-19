<?php

namespace App\Api\V1\Controllers\Admin\Product;

use App\Api\V1\Requests\Admin\ProductRequest;
use App\Api\V1\Transformers\Admin\Product\ProductTransformer;
use App\Repositories\Product\ProductRepositoryContract;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\API\V1\Controllers\Controller;


class ProductController extends Controller {

    /**
     * @var ProductRepositoryContract
     */
    private $productRepositoryContract;

    /**
     * ProductController constructor.
     * @param ProductRepositoryContract $productRepositoryContract
     */
    public function __construct(ProductRepositoryContract $productRepositoryContract)
    {
        $this->productRepositoryContract = $productRepositoryContract;
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
        if ($keyword) {
            $products = $this->productRepositoryContract->search($keyword, compact('brand', 'cat', 'group'));
        } else {
            $products = $this->productRepositoryContract->getProductsPaginated($brand, $cat, $group);
        }

        return $this->response->paginator($products, new ProductTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($product_id)
    {
        $product = $this->productRepositoryContract->getProduct($product_id);

        return $this->response->item($product, new ProductTransformer());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

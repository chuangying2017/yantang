<?php

namespace App\Api\V1\Controllers\Admin\Product;

use App\Api\V1\Requests\Admin\ProductRequest;
use App\Api\V1\Transformers\Admin\Product\ProductTransformer;
use App\Repositories\Product\ProductProtocol;
use App\Repositories\Product\ProductRepositoryContract;
use Illuminate\Http\Request;
use Log;

use App\Http\Requests;
use App\Api\V1\Controllers\Controller;


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
     * show listing products search function
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $brand = $request->input('brand');
        $cat = $request->input('cat');
        $group = $request->input('group');
        $status = $request->input('status');
        $type = $request->input('type');

        $keyword = $request->input('keyword');

        if ($keyword) {
            $products = $this->productRepositoryContract->search($keyword, compact('brand', 'cat', 'group', 'status', 'type'));
        } else {
            $products = $this->productRepositoryContract->getProductsPaginated($brand, $cat, $group, $type, $status);
        }

        foreach ($products as $key=>$product){
            $products[$key]['url'] =  config('services.weixin.redirect_urls.linkWeChatProducts').'mall/subsproducts/'.$product['id'];
        }

        return $this->response->paginator($products, new ProductTransformer());
    }

    /**
     * Store a newly created resource in storage.
     * 创建商品
     * @param ProductRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {

        $product = $this->productRepositoryContract->createProduct($request->all());

        return $this->response->item($product, new ProductTransformer())->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     * 后台编辑 展示单个产品
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($product_id)
    {
        $product = $this->productRepositoryContract->getProduct($product_id);

        return $this->response->item($product, new ProductTransformer());
    }

    /**
     * Update the specified resource in storage.
     * 编辑商品 put 请求方式
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        $product = $this->productRepositoryContract->updateProduct($id, $request->all());

        return $this->response->item($product, new ProductTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->productRepositoryContract->deleteProduct($id);

        return $this->response->noContent();
    }

    //下架
    public function down($product_id)
    {
        $product = $this->productRepositoryContract->updateProductAsDown($product_id);
        return $this->response->item($product, new ProductTransformer());
    }

    //上架
    public function up($product_id)
    {
        $product = $this->productRepositoryContract->updateProductAsUp($product_id);
        return $this->response->item($product, new ProductTransformer());
    }



    public function upf()
    {
        //
    }
}

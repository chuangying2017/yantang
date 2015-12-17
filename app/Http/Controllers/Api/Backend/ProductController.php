<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Requests\Backend\Api\ProductRequest as Request;

use App\Http\Controllers\Controller;
use App\Http\Transformers\ProductTransformer;
use App\Services\ApiConst;
use App\Services\Product\ProductConst;
use App\Services\Product\ProductService;

class ProductController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $category_id = $request->input('cat_id') ?: null;
        $brand_id = $request->input('brand_id') ?: null;
        $sort = ApiConst::decodeSort($request->input('sort'));
        $status = $request->input('status') ?: ProductConst::VAR_PRODUCT_STATUS_UP;

        $products = ProductService::lists(
            $category_id,
            $brand_id,
            ApiConst::PRODUCT_PER_PAGE,
            $sort['order_by'],
            $sort['order_type'],
            $status
        );

        return $this->response->paginator($products, new ProductTransformer());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @structure
     *  - (array) basic_info
     *      - (string) product_no: 商品编码, 后端生成
     *      - *(integer) brand_id
     *      - *(integer) category_id
     *      - *(integer) mechant_id
     *      - *(string) title
     *      - (string) sub_title
     *      - *(integer) price
     *      - (integer) origin_price
     *      - (integer) limit = 0
     *      - (integer) member_discount = 0
     *      - (string) digest
     *      - (string) cover_image
     *      - (string) status =
     *      - *(string) open_status
     *      - (timestamp) open_time
     *      - *(text) attributes
     *          - [name, id, values[[name,id]]
     *      - *(text) detail
     *      - (bool) is_virtual = 0
     *      - (string) origin_id
     *      - (integer) express_fee = 0
     *      - (bool) with_invoice 发票
     *      - (bool) with_care 保修
     *  - *(array) skus
     *  - (array) image_ids
     *  - (array) group_ids
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $product = ProductService::create($data);

        return $this->setStatusCode(201)->array($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = ProductService::show($id);
        $product->show_detail = 1;


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
        $data = $request->all();

        $product = ProductService::update($id, $data);

        return $this->setStatusCode(201)->array($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            ProductService::delete($id);
        } catch (\Exception $e) {
            return $this->respondException($e);
        }

        return $this->respondDelete();
    }
}

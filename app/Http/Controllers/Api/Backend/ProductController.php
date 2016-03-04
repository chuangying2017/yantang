<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Requests\Backend\Api\ProductRequest as Request;

use App\Http\Controllers\Controller;
use App\Http\Transformers\BackendProductTransformer;
use App\Http\Transformers\ProductTransformer;
use App\Models\Merchant;
use App\Services\ApiConst;
use App\Services\Merchant\MerchantService;
use App\Services\Product\ProductConst;
use App\Services\Product\ProductRepository;
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
        $keyword = $request->input('keyword') ? : null;
        $channel_id = $request->input('channel_id') ? : null;

        $products = ProductService::lists(
            $category_id,
            $brand_id,
            ApiConst::PRODUCT_PER_PAGE,
            $sort['order_by'],
            $sort['order_type'],
            $status,
            $keyword,
            $channel_id
        );

        return $this->response->paginator($products, new BackendProductTransformer());
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

        $merchant_id = MerchantService::getMerchantIdByUserId($this->getCurrentAuthUserId());
        $data['merchant_id'] = $merchant_id;

        $product = ProductService::create($data);
        $product->show_detail = 1;

        return $this->response->item($product, new BackendProductTransformer())->setStatusCode(201);
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


        return $this->response->item($product, new BackendProductTransformer());
    }

    public function operate(Request $request)
    {
        $status = $request->input('action');
        $product_ids = $request->input('product_ids');

        $count = ProductRepository::updateStatus($product_ids, $status);

        return $this->response->array(['data' => ['success' => $count]]);
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
        $merchant_id = MerchantService::getMerchantIdByUserId($this->getCurrentAuthUserId());
        $data['merchant_id'] = $merchant_id;

        $product = ProductService::update($id, $data);

        return $this->response->item($product, new BackendProductTransformer());
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
            $this->response->errorInternal($e->getMessage());
        }

        return $this->response->noContent();
    }
}

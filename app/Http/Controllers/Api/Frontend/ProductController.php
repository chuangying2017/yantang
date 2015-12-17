<?php

namespace App\Http\Controllers\Api\Frontend;


use App\Http\Requests\Backend\Api\ProductRequest as Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Transformers\ProductTransformer;
use App\Services\ApiConst;
use App\Services\Product\Fav\FavService;
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
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = ProductService::show($id);
        $product->show_detail = 1;

        if ($user_id = $this->getCurrentAuthUserId()) {
            $product->faved = FavService::checkFav($user_id, $id);
        }

        return $this->response->item($product, new ProductTransformer());
    }

}

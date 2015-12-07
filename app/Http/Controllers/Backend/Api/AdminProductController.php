<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Requests\Backend\Api\ProductRequest as Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminProductController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     *      - *(text) detail
     *      - (bool) is_virtual = 0
     *      - (string) origin_id
     *      - (integer) express_fee = 0
     *      - (bool) with_invoice
     *      - (bool) with_care
     *  - *(array) skus
     *  - (array) image_ids
     *  - (array) group_ids
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

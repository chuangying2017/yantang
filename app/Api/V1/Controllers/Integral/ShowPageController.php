<?php

namespace App\Api\V1\Controllers\Integral;


use App\Api\V1\Transformers\Integral\ClientDetailTransformer;
use App\Api\V1\Transformers\Integral\ClientIntegralTransformer;
use App\Services\Integral\Product\ProductInerface;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ShowPageController extends Controller
{

    protected $product;

    public function __construct(ProductInerface $productInerface)
    {
        $this->product=$productInerface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product_ = $this->product->get_all_product(['status'=>'up'],false,'sort_type','asc');
        $product_->load('product_sku');

        return $this->response->collection($product_, new ClientIntegralTransformer());
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product_one_data = $this->product->get_product($id);

        return $this->response->item($product_one_data, new ClientDetailTransformer());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
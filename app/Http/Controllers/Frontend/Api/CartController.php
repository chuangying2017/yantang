<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Transformers\CartTransformer;
use App\Services\Cart\CartService;

use App\Http\Requests\Frontend\CartRequest as Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CartController extends Controller {


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = $this->getCurrentAuthUserId();
        $carts = CartService::lists($user_id);

        return $this->response->collection($carts, new CartTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $product_sku_id = $request->input('product_sku_id');
            $quantity = $request->input('quantity', 1);
            $user_id = $this->getCurrentAuthUserId();

            $cart = CartService::add($user_id, $product_sku_id, $quantity);


            return $this->response->item($cart, new CartTransformer())->setStatusCode(201);
        } catch (\Exception $e) {
            $this->response->errorInternal($e->getMessage());
        }

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $cart_id)
    {
        $user_id = $this->getCurrentAuthUserId();
        $quantity = $request->input('quantity') ?: 1;

        $cart = CartService::update($cart_id, $user_id, $quantity);

        return $this->response->item($cart, new CartTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($cart_id)
    {
        $user_id = $this->getCurrentAuthUserId();

        CartService::remove($cart_id, $user_id);

        return $this->response->noContent();
    }
}

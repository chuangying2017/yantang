<?php

namespace App\Api\V1\Controllers\Mall;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Transformers\Mall\CartTransformer;
use App\Repositories\Cart\CartRepositoryContract;
use Illuminate\Http\Request;

use App\Http\Requests;

class CartController extends Controller {

    /**
     * @var CartRepositoryContract
     */
    private $cartRepo;

    /**
     * CartController constructor.
     * @param CartRepositoryContract $cartRepo
     */
    public function __construct(CartRepositoryContract $cartRepo)
    {
        $this->cartRepo = $cartRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $carts = $this->cartRepo->getAll();
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
        $cart = $this->cartRepo->addOne(
            $request->input('product_sku_id'),
            $request->input('quantity')
        );

        if ($cart) {
            return $this->response->item($cart, new CartTransformer())->setStatusCode(201);
        }

        $this->response->error('库存不足或其他错误原因,无法添加到购物车', 400);
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
        $cart = $this->cartRepo->updateQuantity($id, $request->input('quantity'));
        return $this->response->item($cart, new CartTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $cart_ids = $request->input('cart_ids');
        if ($cart_ids === 'all') {
            $this->cartRepo->deleteAll();
        }
        $this->cartRepo->deleteMany($cart_ids);
        return $this->response->noContent();
    }
}

<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Services\Cart\CartService;
use App\Services\Orders\OrderGenerator;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OrderController extends Controller {

    /**
     * @var OrderGenerator
     */
    private $orderGenerator;

    /**
     * OrderController constructor.
     * @param OrderGenerator $orderGenerator
     */
    public function __construct(OrderGenerator $orderGenerator)
    {
        $this->orderGenerator = $orderGenerator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function preConfirm(Request $request)
    {
        $carts = $request->input('data');
        $user_id = $this->getUserId();
        $order_products_request['products'] = CartService::take($carts);

        $order_info = $this->orderGenerator->buy($user_id, $order_products_request, $carts);

        return $order_info;
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
     */
    public function store(Request $request)
    {
        $address_id = $request->input('address_id');
        $uuid = $request->input('uuid');
        $memo = $request->input('memo', '');

        $this->orderGenerator->confirm($uuid, $address_id);

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

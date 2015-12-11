<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Transformers\OrderTransformer;
use App\Services\Cart\CartService;
use App\Services\Client\AddressService;
use App\Services\Orders\OrderGenerator;
use App\Services\Orders\OrderService;
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
        $user_id = $this->getCurrentAuthUserId();

        $orders = OrderService::lists($user_id);


        return $this->response->collection($orders, new OrderTransformer());
    }

    public function preConfirm(Request $request)
    {
        $carts = $request->input('data');
        $user_id = $this->getCurrentAuthUserId();
        $order_products_request = CartService::take($carts);

        if ( ! count($order_products_request)) {
            $this->response->errorBadRequest('购买选项不存在');
        }

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
        try {
            $address_id = $request->input('address_id');
            $uuid = $request->input('uuid');
            $memo = $request->input('memo', '');
            $user_id = $this->getCurrentAuthUserId();

            $order = $this->orderGenerator->confirm($uuid, $address_id);

            return $this->response->created(route('api.orders.show', $order['order_no']));
        } catch (\Exception $e) {
            $this->response->errorInternal($e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($order_no)
    {
        $user_id = $this->getCurrentAuthUserId();
        try {
            $order = OrderService::show($user_id, $order_no);
            $order->show_full = 1;


        } catch (\Exception $e) {
            $this->response->errorBadRequest($e->getMessage());
        }

        return $this->response->item($order, new OrderTransformer());
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

<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Transformers\OrderTransformer;
use App\Services\ApiConst;
use App\Services\Orders\OrderManager;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OrderController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $user_id = $request->input('user_id') ?: null;
            $sort = ApiConst::decodeSort($request->input('sort'));
            $status = $request->input('status') ?: null;
            $orders = OrderManager::lists($user_id, $sort['order_by'], $sort['order_type'], ['children', 'address'], $status, ApiConst::ORDER_PER_PAGE);

            return $this->response->paginator($orders, new OrderTransformer());
        } catch (Exception $e) {
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
        try {
            $order = OrderManager::show($order_no);
            $order->show_full = 1;

            return $this->response->item($order, new OrderTransformer());
        } catch (Exception $e) {
            $this->response->errorInternal($e->getMessage());
        }
    }


}

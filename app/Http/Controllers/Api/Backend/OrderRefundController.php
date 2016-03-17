<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Requests\OrderRefundActionRequest;
use App\Services\Orders\OrderProtocol;
use App\Services\Orders\OrderRefund;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OrderRefundController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status = $request->input('status') ?: OrderProtocol::STATUS_OF_RETURN_APPLY;

        $orders = OrderRefund::lists($status);

        return $this->response->array(['data' => $orders]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = OrderRefund::show($id);

        return $this->response->array(['data' => $order]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrderRefundActionRequest $request, $id)
    {
        $action = $request->input('action') ?: OrderProtocol::ACTION_OF_RETURN_REJECT;
        $memo = $request->input('memo');

        if ($action == OrderProtocol::ACTION_OF_RETURN_APPROVE) {
            $order = OrderRefund::approve($id, $memo);
        } else {
            $order = OrderRefund::reject($id, $memo);
        }

        return $this->response->array(['data' => $order]);
    }

    public function done($id)
    {
        $result = OrderRefund::refunding($id);

        return $this->response->array(['data' => $result]);
    }

}

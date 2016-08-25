<?php

namespace App\Api\V1\Controllers\Mall;

use App\API\V1\Controllers\Controller;
use App\Api\V1\Transformers\TempOrderTransformer;
use App\Services\Order\OrderGenerator;
use Illuminate\Http\Request;

use App\Http\Requests;

class CartOrderController extends Controller {

    /**
     * @var OrderGenerator
     */
    private $orderGenerator;

    /**
     * CartOrderController constructor.
     * @param OrderGenerator $orderGenerator
     */
    public function __construct(OrderGenerator $orderGenerator)
    {
        $this->orderGenerator = $orderGenerator;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cart_ids = $request->input('cart_ids');
        $address_id = $request->input('address');

        $temp_order = $this->orderGenerator->buyCart(access()->id(), $cart_ids);

        return $this->response->item($temp_order, new TempOrderTransformer());
    }

    public function show(Request $request, $temp_order_id)
    {
        $temp_order = $this->orderGenerator->getTempOrder($temp_order_id);

        if (!$temp_order) {
            throw new \Exception('订单过期', 404);
        }

        return $this->response->item($temp_order, new TempOrderTransformer());
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $temp_order_id)
    {
        $address_id = $request->input('address') ?: null;

        if ($address_id) {
            $temp_order = $this->orderGenerator->setAddress($temp_order_id, $address_id);
            return $this->response->item($temp_order, new TempOrderTransformer());
        }

        $ticket_id = $request->input('ticket') ?: null;

        if ($ticket_id) {
            $temp_order = $this->orderGenerator->useCoupon($temp_order_id, $ticket_id);
            return $this->response->item($temp_order, new TempOrderTransformer());
        }

        $this->response->errorBadRequest();
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

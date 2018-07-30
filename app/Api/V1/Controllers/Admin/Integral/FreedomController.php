<?php

namespace App\Api\V1\Controllers\Admin\Integral;

use App\Api\V1\Transformers\Integral\Admin\AdminShipmentsTransformer;
use App\Repositories\Integral\OrderHandle\OrderIntegralInterface;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FreedomController extends Controller
{
    protected $shipping;

    public function __construct(OrderIntegralInterface $orderIntegral)
    {
        $this->shipping = $orderIntegral;
    }

    public function Shipping_management(Request $request) //发货 管理
    {

        $gain_data = $this->shipping->where_express($request->all());

        return $this->response->paginator($gain_data, new AdminShipmentsTransformer())->setStatusCode(201);
    }

    public function Shipping_order_detail($id)
    {
        $order_detail = $this->shipping->first($id);

        return $this->response->item($order_detail, new AdminShipmentsTransformer());
    }
}

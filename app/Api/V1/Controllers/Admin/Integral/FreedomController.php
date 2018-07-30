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

    /**
     * @api {get} /admin/integral/freedomThe/shippingManagement Request shipping management information
     * @apiName GetUser
     * @apiGroup User
     *
     * @apiParam {Number} keywords 关键字.
     * @apiParam {date} start_time  开始时间
     * @apiParam {date} end_time    结束时间
     *
     * @apiSuccess {array} arrayName 请求成功就是一个数据集数组或者空的数组.
     */
    public function Shipping_management(Request $request) //发货 管理
    {

        $gain_data = $this->shipping->where_express($request->all());

        return $this->response->paginator($gain_data, new AdminShipmentsTransformer())->setStatusCode(201);
    }

    /**
     * @api {get} /admin/integral/freedomThe/{id}/shippingOrderDetail 发货详情
     * @apiName GetIntegral
     * @apiGroup Integral
     *
     *
     * @apiSuccess {array} arrayName 请求成功就是一个数据集数组或者空的数组.
     */
    public function Shipping_order_detail($id)
    {
        $order_detail = $this->shipping->first($id);

        return $this->response->item($order_detail, new AdminShipmentsTransformer());
    }
}

<?php

namespace App\Api\V1\Controllers\Admin\Integral;

use App\Api\V1\Requests\Integral\AdminIntegralCardRequest;
use App\Api\V1\Transformers\Integral\Admin\AdminShipmentsTransformer;
use App\Repositories\Integral\OrderHandle\OrderIntegralInterface;
use App\Repositories\Integral\Supervisor\Supervisor;
use App\Services\Chart\ExcelService;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class FreedomController extends Controller
{
    protected $shipping;

    protected $operation;

    public function __construct(OrderIntegralInterface $orderIntegral,Supervisor $supervisor)
    {
        $this->shipping = $orderIntegral;
        $this->operation = $supervisor;
    }

    /**
     * @api {get} /admin/integral/freedomThe/shippingManagement Request shipping management information
     * @apiName GetUser
     * @apiGroup User
     *
     * @apiParam {Number} keywords 关键字.
     * @apiParam {date} start_time  开始时间
     * @apiParam {date} end_time    结束时间
     * @apiParam {string} export    如果值等于all导出
     *
     * @apiSuccess {array} arrayName 请求成功就是一个数据集数组或者空的数组.
     */
    public function Shipping_management(Request $request) //发货 管理
    {

        $gain_data = $this->shipping->where_express($request->all());
        if ($request->input('export') == 'all')
        {
            return ExcelService::downloadIntegralOrder($gain_data,null);
        }
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

    /**
     * @api {post} /admin/integral/freedomThe/{order_id}/convert_confirm 确定兑换
     * @apiName GetExpressConfirm
     * @apiGroup Integral
     * @apiParam {string} express 快递公司名称不能为空
     * @apiParam {Number} expressOrder 快递单号数字或字符串不能为空
     * @apiSuccess {statusCode} status 成功返回成功201
     * @apiError InternalError program error not update 500
     */
    public function convert_confirm($order_id,Request $request)
    {
        $this->shipping->update_order($order_id,$request->all());

        return $this->response->noContent()->statusCode(201);
    }

    /**
     * @api {post} /admin/integral/freedomThe/card_manager 添加积分卡
     * @apiName GetCardManager
     * @apiGroup Integral
     * @apiParam {string} name 名称|字符串类型不能为空
     * @apiParam {float} give 赠送积分|浮点类型不能为空
     * @apiParam {date} start_time 开始时间2017-01-01 13:00:01 不能为空必须大于等于结束时间
     * @apiParam {date} end_time 结束时间2018-01-02 14:00:01 不能为空
     * @apiParam {string} status 不能为空 show上架 | hide隐藏
     * @apiParam {string} type  不能为空 limits限制发布数量 \ loose无限制
     * @apiParam {url} cover_image  图片地址 能为空
     * @apiParam {number} issue_num 发布数量数值类型
     * @apiParam {integer} draw_num 限制会员领取数量
     *
     * @apiSuccess {statusCode} status 成功后返回200状态code
     * @apiError InternalError parameters type have Wrong
     *
     *
     * @apiDescription 每个地段必须按照规定的参数类型，传值否者无法生效！
     *
     */
    public function card_manager(AdminIntegralCardRequest $request)
    {

       $this->operation->create($request->all());

        return $this->response->noContent()->statusCode(201);
    }
}

<?php

namespace App\Api\V1\Controllers\Admin\Integral;

use App\Api\V1\Requests\Integral\AdminIntegralCardRequest;
use App\Api\V1\Transformers\Integral\Admin\AdminShipmentsTransformer;
use App\Api\V1\Transformers\Integral\IntegralCardTransformer;
use App\Api\V1\Transformers\Integral\SignRuleTransformer;
use App\Repositories\Integral\OrderHandle\OrderIntegralInterface;
use App\Repositories\Integral\SignRule\SignClass;
use App\Repositories\Integral\Supervisor\Supervisor;
use App\Repositories\Other\Protocol;
use App\Repositories\setting\SetMode;
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
     * @apiParam {string} status DropShip待发货Delivered待收货confirm已完成
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
     * @apiParam {integer} mode 设置0不限制 1限制新用户
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

    /**
     * @api {get} /admin/integral/freedomThe/integralCardShow 获取积分卡
     *
     * @apiName GetIntegralCardShowAll
     * @apiGroup Integral
     * @apiSuccess {statusCode} status 成功返回200并与多维数组|空数组
     * @apiError DataNotFound Maybe there's no data in the table
     *
     */
    public function card_show()
    {
        $card = $this->operation->get_all();

        return $this->response->paginator($card, new IntegralCardTransformer())->statusCode(200);
    }

    /**
     * @api {get} /admin/integral/freedomThe/IntegralCardFind/{card_id} 获取id积分卡
     * @apiName GetIntegralCardId
     * @apiGroup Integral
     *
     * @apiSuccess {statusCode} status 返回数组
     * @apiError CardNotFound error message or where no be verify
     * @apiDescription 返回数据或者一直报错 id必须是数值
     */
    public function card_find($card_id)
    {
        $card = $this->operation->find($card_id);

        return $this->response->item($card, new IntegralCardTransformer());
    }

    /**
     * @api {post} /admin/integral/freedomThe/{card_id}/card_update 积分卡id编辑
     * @apiName GetCardUpdate
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
    public function card_update($card_id, AdminIntegralCardRequest $request)
    {
        $this->operation->update($card_id, $request->all());

        return $this->response->noContent()->statusCode(201);
    }

    /**
     * @api {put} /admin/integral/freedomThe/{card_id}/card_delete 删除积分卡
     * @apiName GetIntegralCardDel
     * @apiGroup Integral
     *
     * @apiSuccess {statusCode} status 成功返回201状态码
     * @apiError InternalError Parameters maybe is error or other exception
     * @apiDescription 请求方式必须与描述得一致 根据url的card_id必须是数值
     */
    public function card_destroy($card_id)
    {
        $this->operation->delete($card_id);

        return $this->response->noContent()->statusCode(201);
    }

    /**
     * @api {get} /admin/integral/freedomThe/sign/signGet 获取签到规则
     * @apiName GetIntegralSignRule
     * @apiGroup Integral
     * @apiSuccess {array} array 成功返回数组或空数组
     * @apiDescription 必须安装请求方式去获取无需带任何参数
     */
    public function sign_get(SignClass $signClass)
    {
        $signData = $signClass
            ->setPath(config('services.localStorageFile.path'))
            ->setFile(config('services.localStorageFile.SignRule'))
            ->get();

        return response()->json($signData,200);
    }

    /**
     * @api {put} /admin/integral/freedom/sign/signUpdate
     * @apiName GetIntegralSignUpdate
     * @apiGroup Integral
     * @apiParam {numeric} status 开启签到0关闭1开启 not null
     * @apiParam {numeric} retroactive 默认开启补签1开启0关闭 not null
     * @apiParam {string} state 签到规则说明 can null
     * @apiParam {array} extend_rule 用这种方式{"sex": "girl"}提交
     * @apiParam {numeric} extend_rule.compensateIntegral 扣除补签积分
     * @apiParam {string} extend_rule.firstRewards 首次奖励{status:1开启0关闭,rewards:100,everyday:10}
     * @apiParam {string} extend_rule.continuousOne 连续签到{status:1开启0关闭,days:10,rewards:10}
     * @apiParam {string} extend_rule.continuousTwo 连续签到{status:1开启0关闭,days:20,rewards:20}
     * @apiParam {string} extend_rule.continuousThree 连续签到{status:1开启0关闭,days:30,rewards:30}
     * @apiParam {string} extend_rule.continuousSum 总签奖励{status:1开启0关闭,days:50,rewards:100}
     * @apiParam {string} extend_rule.autorelease 特殊奖励{status:1开启0关闭,title:年庆,rewards:1000,date:2018-05-06}
     * @apiSuccess {statusCode} statusCode successfully condition return 201 code or return 500
     * @apiDescription if successfully return 201 No person is fail fatal error internal problem
     */
    public function sign_update(Request $request,SignClass $signClass)
    {
        $signClass
            ->setPath(config('services.localStorageFile.path'))
            ->setFile(config('services.localStorageFile.SignRule'))
            ->rewriteData($request->except('token'));

        return $this->response->noContent()->setStatusCode(201);
    }

    /**
     * @api {get} /admin/integral/validityGet 积分有效期Get
     * @apiName GetIntegralValidity
     * @apiGroup Integral
     * @apiSuccess {object} object 返回对象
     * @apiSuccess {array} object.setting 返回设置数组
     * @apiSuccess {array} object.protocol 返回协议数组
     */
    public function integral_validity(SetMode $setMode, Protocol $protocol)
    {
        return $this->response->array(
            ['setting' => $setMode->getSetting(3),'protocol'=> $protocol->getAllProtocol([3,4])]
        );
    }

    /**
     * @api {put} /admin/integral/validity/{id}/Update 积分有效期更新
     * @apiName GetIntegralValidityUpdate
     * @apiGroup Integral
     * @apiParam {object} setting 对象类型setting:{year:1}
     * @apiParam {numeric} setting.year 数值类型
     * @apiParam {object} where 对象where:{type:3}更新协议条件
     * @apiParam {numeric} where.type 数值类型
     * @apiParam {object} protocol 协议对象
     * @apiParam {string} protocol.protocol_content 字符类型text方式
     * @apiSuccess {statusCode} statusCode Successfully status code 201
     * @apiError InternalError possible internal code error
     * @apiDescription 数值id对应setting里的id
     */
    public function validity_update($id,Request $request, SetMode $setMode, Protocol $protocol)
    {
        $setMode->updateSet($id,$request->input('setting'));
        $protocol->updateProtocol($request->input('where'),$request->input('protocol'));
        return $this->response->noContent()->statusCode(201);
    }

}

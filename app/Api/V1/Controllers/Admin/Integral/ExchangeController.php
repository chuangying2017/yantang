<?php

namespace App\Api\V1\Controllers\Admin\Integral;

use App\Api\V1\Requests\Integral\ExchangeRequest;
use App\Api\V1\Transformers\Integral\ExchangeTransformer;
use App\Repositories\Integral\ShareCarriageWheel\ShareAccessRepositories;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ExchangeController extends Controller
{
    public $convert;

    public function __construct(ShareAccessRepositories $accessRepositories)
    {
        $this->convert = $accessRepositories;
    }

    /**
     * Display a listing of the resource.
     *
     * @api {get} /admin/integral/exchangeThe 获取全部兑换
     *
     * @apiName GetExchangeGetAll
     * @apiGroup Integral
     * @apiSuccess {array} array successfully of return many array | or null array
     * @apiDescription 本接口返回一个分页状态
     */
    public function index()
    {
        $fetchAll = $this->convert->get_all();

        return $this->response->paginator($fetchAll,new ExchangeTransformer())->statusCode(201);
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
     * Update the specified resource in storage.
     *
     * @api {post} /admin/integral/exchangeThe 积分兑卷添加
     * @apiParam {numeric} cost_integral 数值类型 消耗积分
     * @apiParam {numeric} promotions_id 数值类型 优惠券id
     * @apiParam {date} valid_time 开始时间2018-08-03 13:08:08
     * @apiParam {date} deadline_time 结束时间2018-08-04 13:08:08必须大于开始时间
     * @apiParam {integer} status 0下架1上架
     * @apiParam {integral} issue_num 发布数量默认整形不能小于0
     * @apiParam {integer} draw_num 已经领取数量
     * @apiParam {integer} remain_num 剩余数量
     * @apiParam {integral} delayed 领取后的延时 时长单位小时
     * @apiParam {url}  cover_image 上传图片地址可为空
     * @apiParam {integer} member_type 限制新旧会员领取0全部可领取1新会员不可领取
     * @apiParam {integer} limit_num 限制会员兑换数量
     *
     * @apiName GetExchangeAddition
     * @apiGroup Integral
     *
     * @apiSuccess {statusCode} status 成功返回201状态
     * @apiError InternalError possible problem appear in background program code
     *
     * @apiDescription 请严格按照上面描述传参 否者可能内部出错无法插入
     */
    public function store(ExchangeRequest $request)
    {
        $this->convert->updateOrCreate(null,$request->all());

        return $this->response->noContent()->statusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @api {get} /admin/integral/exchangeThe/{id} 获取id兑换
     * @apiName GetExchangeShow
     * @apiGroup Integral
     * @apiSuccess {array} array request successfully return array OR empty array
     * @apiError IdNotFound possible internal code fatal error
     *
     *
     */
    public function show($id)
    {
        $argc = $this -> convert -> find($id);

        return $this->response->item($argc, new ExchangeTransformer())->statusCode(201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @api {put} /admin/integral/exchangeThe/{id} 积分兑卷更新
     * @apiParam {numeric} cost_integral 数值类型 消耗积分
     * @apiParam {numeric} promotions_id 数值类型 优惠券id
     * @apiParam {date} valid_time 开始时间2018-08-03 13:08:08
     * @apiParam {date} deadline_time 结束时间2018-08-04 13:08:08必须大于开始时间
     * @apiParam {integer} status 0下架1上架
     * @apiParam {integral} issue_num 发布数量默认整形不能小于0
     * @apiParam {integer} draw_num 已经领取数量
     * @apiParam {integer} remain_num 剩余数量
     * @apiParam {integral} delayed 领取后的延时 时长单位小时
     * @apiParam {url}  cover_image 上传图片地址可为空
     * @apiParam {integer} member_type 限制新旧会员领取0全部可领取1新会员不可领取
     * @apiParam {integer} limit_num 限制会员兑换数量
     *
     * @apiName GetExchange
     * @apiGroup Integral
     *
     * @apiSuccess {statusCode} status 成功返回201状态
     * @apiError InternalError possible problem appear in background program code
     *
     * @apiDescription 请严格按照上面描述传参 否者可能内部出错无法插入
     */
    public function update(ExchangeRequest $request, $id)
    {
        $this->convert->updateOrCreate($id,$request->all());

        return $this->response->noContent()->statusCode(201);
    }

    /**
     * @api {delete} /admin/integral/exchangeThe/{id} 积分兑卷删除
     * @apiName GetExchangeDelete
     * @apiGroup Integral
     *
     * @apiSuccess {statusCode} status deleteSuccessfully return status code 201
     * @apiError internalError internal fatal error possible problem code id not found
     *
     * @apiDescription 注意地址上的id必须是数值
     */
    public function destroy($id)
    {
        $this->convert->delete($id);

        return $this->response->noContent()->statusCode(201);
    }
}

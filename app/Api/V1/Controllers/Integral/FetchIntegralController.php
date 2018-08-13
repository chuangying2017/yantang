<?php

namespace App\Api\V1\Controllers\Integral;

use App\Api\V1\Transformers\Integral\IntegralCardTransformer;
use App\Repositories\Integral\Supervisor\Supervisor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FetchIntegralController extends Controller
{

    protected $operation;

    public function __construct(Supervisor $supervisor)
    {
        $this->operation = $supervisor;
    }

    /**
     * Display a listing of the resource.
     * @api {get} /integral/fetchIntegral 获取积分卡信息
     *
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     * @api {get} /integral/fetchIntegral/{id} 获取id积分卡
     * @apiName GetIntegralCardFrontDesk
     * @apiGroup FrontDesk
     * @apiSuccess {object} object 成功返回对象类型
     * @apiSuccess {string} object.name 名称
     * @apiSuccess {string} object.give 赠送积分
     * @apiSuccess {date} object.start_time 开始时间
     * @apiSuccess {date} object.end_time  结束时间
     * @apiSuccess {string} object.status  show上架 | hide隐藏
     * @apiSuccess {string} object.type  limits限制发布数量 \ loose无限制
     * @apiSuccess {url} object.cover_image 展示图片
     */
    public function show($id)
    {
        $fetchData = $this->operation->find($id);

        return $this->response->item($fetchData, new IntegralCardTransformer());
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

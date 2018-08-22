<?php

namespace App\Api\V1\Controllers\Integral;

use App\Api\V1\Requests\Integral\SignRequest;
use App\Api\V1\Transformers\Integral\SignTransformer;
use App\Repositories\Integral\SignHandle\SignVerifyClass;
use App\Repositories\Integral\SignRule\SignClass;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class SignController extends Controller
{
    public $sign;

    public function __construct(SignVerifyClass $signVerifyClass)
    {
        $this->sign = $signVerifyClass;
    }

    /**
     * Display a listing of the resource.
     *
     * @api {get} /integral/integralSignMonthAll 获取当月签到
     * @apiName GetIntegralSignMonthAllData
     * @apiGroup FrontDesk
     * @apiParam {date} date 日期类型必须是2018-02-09 格式 | 或者返回空数组
     * @apiSuccess {object} object 成功返回对象类型
     * @apiSuccess {numeric} object.total 当月总签到
     * @apiSuccess {numeric} object.continuousSign 连续签到次数
     * @apiSuccess {object}  object.signDay 多维对象签到天数
     * @apiSuccess {numeric} signDay.day 对应的当前的号数
     */
    public function index(SignRequest $request)
    {
        $selectDate = $request->input('date',Carbon::now()->toDateString());

        $data = $this->sign->fetchSignMonth($selectDate);

        return $this->response->item($data, new SignTransformer());
    }

    /**
     * @api {get} /integral/integralSignGet 点击签到
     * @apiName GetIntegralSignGet
     * @apiGroup FrontDesk
     * @apiSuccess {object} object 成功返回对象{status:1,message:successfully}
     * @apiSuccess {object} object 失败返回对象{status:2,message:errorMessage}
     */
    public function SignGet()
    {
      $result = $this->sign->verifyUserToday();

      return $this->response->array($result);
    }

    public function SignRepair($day)//补签天数
    {

    }

    /**
     * @api {get} /integral/integralGetRule
     * @apiName GetIntegralRuleFrontDesk
     * @apiGroup FrontDesk
     * @apiSuccess {object} object 成功返回数据对象
     */
    public function SignGetRule(SignClass $signClass)
    {
        $signData = $signClass
            ->setPath(config('services.localStorageFile.path'))
            ->setFile(config('services.localStorageFile.SignRule'))
            ->get();

        return response()->json($signData,200);
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
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

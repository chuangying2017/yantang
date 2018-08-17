<?php

namespace App\Api\V1\Controllers\Integral;

use App\Api\V1\Transformers\Integral\ExchangeTransformer;
use App\Models\Integral\IntegralConvertCoupon;
use App\Models\Integral\IntegralRecord;
use App\Repositories\Integral\ShareCarriageWheel\ShareAccessRepositories;
use App\Repositories\Other\Protocol;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class IntegralCouponController extends Controller
{
    protected $convert;

    public function __construct(ShareAccessRepositories $accessRepositories)
    {
        $this->convert = $accessRepositories;
    }

    /**
     * @api {get} /integral/integralCard 兑换卷列表
     * @apiName GetIntegralCardList
     * @apiGroup FrontDesk
     * @apiSuccess {object} object 返回对象
     * @apiSuccess {string} object.name 标题
     * @apiSuccess {string} object.description 描述
     * @apiSuccess {numeric} object.cost_integral 兑换消耗积分
     * @apiSuccess {float} object.couponAmount 优惠金额
     * @apiSuccess {url} object.cover_image 图片链接
     * @apiSuccess {numeric} object.delayed 兑换后小时为单位
     * @apiError InternalError possible internal error code 500 reject msg
     */
    public function get_exchange()
    {
        $object = $this->convert->get_convert();

        $object->load('promotions.rules');

        return $this->response->collection($object,new ExchangeTransformer());
    }

    /**
     * @api {get} /integral/integralCard/{id} 兑换卷获取id
     * @apiName GetIntegralCardFind
     * @apiGroup FrontDesk
     * @apiSuccess {object} object 返回对象
     * @apiSuccess {object} object.name 标题
     * @apiSuccess {string} object.description 描述
     * @apiSuccess {numeric} object.cost_integral 兑换消耗积分
     * @apiSuccess {float} object.couponAmount 优惠金额
     * @apiSuccess {url} object.cover_image 图片链接
     * @apiSuccess {numeric} object.delayed 兑换后小时为单位
     */
    public function get_show($id)
    {
        $object = $this->convert->find($id);

        $object->load('promotions.rules');

        return $this->response->item($object, new ExchangeTransformer());
    }

    /**
     * @api {put} /integral/integralFetchCoupon/{convertId} 积分兑换卷
     * @apiName GetIntegralConvert
     * @apiGroup FrontDesk
     * @apiSuccess {statusCode} status 成功返回状态码200
     * @apiError InternalError return status code 500 and error messages
     */
    public function put_integral($convertId)
    {
       $returnVal = $this->convert->convertCoupon([
            'user_id'   =>  access()->id(),
            'convertId' => $convertId
        ]);

        return $this->response->array(verify_dataMessage($returnVal));
    }

    /**
     * @api {get} /integral/integralProtocol 获取协议
     * @apiName GetIntegralProtocol
     * @apiGroup FrontDesk
     * @apiSuccess {object} object 成功返回对象
     */
    public function pull_protocol(Protocol $protocol)
    {
        return $this->response->array(
            $protocol->getAllProtocol([3,4,5])
        );
    }

    /**
     * @api {get} /integral/integralRecord?page=1 积分明细
     * @apiName GetIntegralRecord
     * @apiGroup FrontDesk
     * @apiSuccess {object} object 成功返回对象或空数组
     * @apiSuccess {string} object.name 名称
     * @apiSuccess {date} object,created_at 创建时间
     * @apiSuccess {string} object.integral 积分
     * @apiDescription page如果不传默认是第一页每页20条
     */
    public function pull_integralRecord(Request $request,IntegralRecord $integralRecord)
    {

        $page = $request->input('page',1);

        $userId = access()->id();

        $data['data'] = $integralRecord->where('user_id','=',$userId)->forPage($page,20)->orderBy('id','desc')->get()->toArray();

        if (!empty($data))
        {
            $data['total_integral'] = $integralRecord->where('user_id','=',$userId)->where('integral','like','%+%')->sum('integral');
        }

        return $this->response->array($data);
    }
}


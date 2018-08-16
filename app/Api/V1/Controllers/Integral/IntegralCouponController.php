<?php

namespace App\Api\V1\Controllers\Integral;

use App\Api\V1\Transformers\Integral\ExchangeTransformer;
use App\Models\Integral\IntegralConvertCoupon;
use App\Repositories\Integral\ShareCarriageWheel\ShareAccessRepositories;
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

    public function put_integral($convertId)
    {
       $returnVal = $this->convert->convertCoupon([
            'user_id'   =>  access()->id(),
            'convertId' => $convertId
        ]);

       if (is_string($returnVal))
       {
           return $this->response->error($returnVal,500);
       }
       return $this->response->noContent()->statusCode(201);
    }
}

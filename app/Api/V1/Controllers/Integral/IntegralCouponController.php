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
     * @return \Dingo\Api\Http\Response
     */
    public function get_exchange()
    {
        $object = $this->convert->get_convert();

        return $this->response->collection($object,new ExchangeTransformer());
    }
}

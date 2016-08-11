<?php

namespace App\Api\V1\Controllers\Admin\Promotion;

use App\API\V1\Transformers\Admin\Promotion\CouponTransformer;
use App\Repositories\Promotion\Coupon\CouponRepositoryContract;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CouponController extends Controller {

    /**
     * @var CouponRepositoryContract
     */
    private $couponRepo;

    /**
     * CouponController constructor.
     * @param CouponRepositoryContract $couponRepo
     */
    public function __construct(CouponRepositoryContract $couponRepo)
    {

        $this->couponRepo = $couponRepo;
    }

    public function index(Request $request)
    {
        $coupons = $this->couponRepo->getAll();

        return $coupons;
    }

    public function store(Request $request)
    {
        $coupon = $this->couponRepo->create($request->all());

        return $this->response->item($coupon, new CouponTransformer())->setStatusCode(201);
    }


}

<?php

namespace App\Api\V1\Controllers\Promotion;

use App\Api\V1\Transformers\Promotion\CouponTransformer;
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
		
        $coupons = $this->couponRepo->getAllPaginated(true);
        return $this->response->paginator($coupons, new CouponTransformer());
    }

    public function show($id)
    {
		
        $coupons = $this->couponRepo->get($id);
        return $this->response->item($coupons, new CouponTransformer());
    }
    

}

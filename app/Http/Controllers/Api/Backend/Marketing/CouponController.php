<?php

namespace App\Http\Controllers\Api\Backend\Marketing;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Api\MarketingCouponRequest as Request;
use App\Http\Transformers\CouponTransformer;
use App\Services\ApiConst;
use App\Services\Marketing\Items\Coupon\CouponManager;
use App\Services\Marketing\MarketingProtocol;
use App\Services\Marketing\MarketingRepository;


class CouponController extends Controller {


    /**
     * @var CouponManager
     */
    private $couponManager;

    /**
     * @param CouponManager $couponManager
     */
    public function __construct(CouponManager $couponManager)
    {
        $this->couponManager = $couponManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coupons = $this->couponManager->lists(null, ApiConst::COUPON_PER_PAGE);

        return $this->response->paginator($coupons, new CouponTransformer());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        try {
            $coupon = $this->couponManager->create($input);
        } catch (\Exception $e) {
            $this->response->errorInternal();
        }

        return $this->response->item($coupon, new CouponTransformer())->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $coupon = $this->couponManager->show($id);

        return $this->response->item($coupon, new CouponTransformer());
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $coupon_id)
    {
        $input = $request->all();

        $coupon = $this->couponManager->update($coupon_id, $input);

        return $this->response->item($coupon, new CouponTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $result = $this->couponManager->delete($id);
        } catch (\Exception $e) {
            $this->response->errorForbidden($e->getMessage());
        }

        return $this->response->noContent();
    }

}

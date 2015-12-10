<?php

namespace App\Http\Controllers\Backend\Api\Marketing;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Api\MarketingCouponRequest as Request;
use App\Http\Transformers\CouponTransformer;
use App\Services\ApiConst;
use App\Services\Marketing\Items\Coupon\CouponManager;
use App\Services\Marketing\MarketingProtocol;
use App\Services\Marketing\MarketingRepository;


class AdminCouponController extends Controller {


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

        return $this->response->paginator($coupons, new CouponTransformer);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        try {
            $coupon_id = $this->couponManager->create($input);
        } catch (\Exception $e) {
            $this->response->errorInternal();
        }

        return $this->response->created(version('v1')->route('api.admin.marketing.coupons.show', $coupon_id));
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
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}

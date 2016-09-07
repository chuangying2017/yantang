<?php

namespace App\Api\V1\Controllers\Admin\Promotion;

use App\API\V1\Transformers\Admin\Promotion\CouponTransformer;
use App\Repositories\Promotion\Coupon\CouponRepositoryContract;
use App\Services\Promotion\PromotionProtocol;
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
        $coupons = $this->couponRepo->getAllPaginated(false);

        return $this->response->paginator($coupons, new CouponTransformer());
    }

    public function store(Request $request)
    {
        $coupon = $this->couponRepo->create($request->all());

        $coupon->load('rules', 'counter');

        return $this->response->item($coupon, new CouponTransformer())->setStatusCode(201);
    }

    public function update(Request $request, $promotion_id)
    {
        $active = PromotionProtocol::validActive($request->input('active'));
        $coupon = $this->couponRepo->updateActiveStatus($promotion_id, $active);

        return $this->response->item($coupon, new CouponTransformer());
    }

    public function show($coupon_id)
    {
        $coupon = $this->couponRepo->get($coupon_id, true);

        $coupon->load('rules', 'counter');

        return $this->response->item($coupon, new CouponTransformer());
    }

    public function destroy($coupon_id)
    {
        $this->couponRepo->delete($coupon_id);

        return $this->response->noContent();
    }

}

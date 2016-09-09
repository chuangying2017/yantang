<?php

namespace App\Api\V1\Controllers\Admin\Promotion;

use App\Repositories\Promotion\TicketRepositoryContract;
use App\Services\Promotion\CouponService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TicketController extends Controller {

    /**
     * @var CouponService
     */
    private $couponService;

    /**
     * TicketController constructor.
     * @param CouponService $couponService
     */
    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }


    public function store(Request $request)
    {
        $user_ids = $request->input('user_ids');
        $promotion_id = $request->input('coupon_id');

        $success = $this->couponService->dispatchWithoutCheck($user_ids, $promotion_id);

        return $this->response->accepted()->setStatusCode(200);
    }


}

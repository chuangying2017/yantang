<?php

namespace App\Api\V1\Controllers\Tool;

use Illuminate\Http\Request;

use App\Services\Promotion\CouponService;
use App\Repositories\Auth\User\EloquentUserRepository;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class GiftcardController extends Controller {
    public function send(Request $request, CouponService $couponService, EloquentUserRepository $userRepo)
    {
       try{
            $open_id = $request->input('open_id');
            $reveiver_id = $userRepo->findUserIdByProviderId($open_id,'weixin');
            if(!$reveiver_id){
                return $this->errorBadRequest('无此用户');
            }
            $promotion_id = $request->input('coupon_id');
            $result = $couponService->dispatchGiftcard($reveiver_id, $promotion_id);
        }
        catch( \Exception $e ){
            \Log::error( 'Failed to dispatch giftcard. '.$e );
            return $this->errorInternal('领取失败');
        }

        return $this->response->created();
    }
}

<?php

namespace App\Api\V1\Controllers\Tool;

use Illuminate\Http\Request;

use App\Services\Promotion\CouponService;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GiftcardController extends Controller {

    public function send(Request $request, CouponService $couponService)
    {
       try{
            $reveiver_id = $request->input('user_id');
            $promotion_id = $request->input('promotion_id');
            $result = $couponService->dispatchGiftcard($reveiver_id, $promotion_id);
        }
        catch( \Exception $e ){
            \Log::error( 'Failed to dispatch giftcard. '.$e );
        }

        return $this->response->noContent()->setStatusCode(201);
    }
}

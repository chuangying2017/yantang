<?php

namespace App\Api\V1\Controllers\Tool;

use Illuminate\Http\Request;

use App\Services\Promotion\GiftcardService;
use App\Api\V1\Transformers\Promotion\GiftcardTransformer;
use App\Repositories\Auth\User\EloquentUserRepository;
use App\Repositories\Promotion\Giftcard\EloquentGiftcardRepository;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class GiftcardController extends Controller {
    public function index(Request $request, EloquentGiftcardRepository $giftcardRepo){
        $giftcards = $giftcardRepo->getAllPaginated(true);
        return $this->response->paginator($giftcards, new GiftcardTransformer());
    }

    public function store(Request $request, GiftcardService $giftcardService, EloquentUserRepository $userRepo)
    {
       try{
            $open_id = $request->input('open_id');
            $reveiver_id = $userRepo->findUserIdByProviderId($open_id,'weixin');
            if(!$reveiver_id){
                return $this->errorBadRequest('无此用户');
            }
            $promotion_id = $request->input('coupon_id');
            $result = $giftcardService->dispatchGiftcard($reveiver_id, $promotion_id);
        }
        catch( \Exception $e ){
            \Log::error( 'Failed to dispatch giftcard. '.$e );
            return $this->errorInternal('领取失败');
        }

        return $this->response->created();
    }
}

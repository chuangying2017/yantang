<?php namespace App\Services\Promotion;

use App\Services\Promotion\Support\PromotionAbleUserContract;

interface PromotionDispatcher {

    public function dispatch(PromotionAbleUserContract $user, $promotion_id);

    public function dispatchWithoutCheck($user_id, $promotion_id);

}

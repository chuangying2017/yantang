<?php namespace App\Services\Promotion;

interface PromotionDispatchContact {

    public function dispatch($promotion_id, $user_id);

}

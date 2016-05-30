<?php namespace App\Services\Promotion;

interface PromotionUsedContract {

    public function used($promotions, $items, $user, $order);

}

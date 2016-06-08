<?php namespace App\Services\Promotion;
class CouponService extends PromotionServiceAbstract implements PromotionDispatchContact {

    public function dispatch($promotion_id, $user_id)
    {

    }

    public function check($items, $promotion_id)
    {
        // TODO: Implement check() method.
    }

    public function used($promotions, $items, $user, $order)
    {
        // TODO: Implement used() method.
    }
}

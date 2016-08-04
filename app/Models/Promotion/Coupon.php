<?php namespace App\Models\Promotion;

use App\Services\Promotion\PromotionProtocol;

class Coupon extends PromotionAbstract {

    const TYPE_OF_PROMOTION = PromotionProtocol::TYPE_OF_COUPON;

    /*
     * Relations
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'coupon_id', 'id');
    }

}

<?php namespace App\Repositories\Promotion\Giftcard;

use App\Models\Promotion\Giftcard;
use App\Repositories\Promotion\PromotionRepositoryAbstract;

class EloquentGiftcardRepository extends PromotionRepositoryAbstract implements GiftcardRepositoryContract {

    protected function init()
    {
        $this->setModel(Giftcard::class);
    }

    public function get($promotion_id, $with_detail = true)
    {
        $giftcard = $promotion_id instanceof Giftcard ? $promotion_id : Giftcard::query()->findOrFail($promotion_id);

        return $giftcard;
    }
}

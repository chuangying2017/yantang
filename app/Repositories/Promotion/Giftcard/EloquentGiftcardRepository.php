<?php namespace App\Repositories\Promotion\Giftcard;

use App\Models\Promotion\Giftcard;
use App\Repositories\Promotion\PromotionRepositoryAbstract;
use App\Repositories\Promotion\Traits\Counter;

class EloquentGiftcardRepository extends PromotionRepositoryAbstract implements GiftcardRepositoryContract {
    use Counter;

    protected function init()
    {
        $this->setModel(Giftcard::class);
    }

    protected function attachRelation($coupon_id, $data)
    {
        $counter = $this->createCounter($coupon_id, $data['total'], $data['effect_days']);
    }

    protected function updateRelation($coupon_id, $data)
    {
        if (isset($data['total']) && isset($data['effect_days'])) {
            $this->updateCounter($coupon_id, $data['total'], $data['effect_days']);
        }
    }

    public function getUsefulPromotions()
    {
        $tickets = $this->ticketRepo->getCouponTicketsOfUser(access()->id(), PromotionProtocol::STATUS_OF_TICKET_OK, false);

        $origin_coupons = $this->getCouponsById($tickets->pluck('promotion_id')->all());

        $coupons = [];

        foreach ($tickets as $ticket_key => $ticket) {
            foreach ($origin_coupons as $coupon_key => $coupon) {
                if ($ticket['promotion_id'] == $coupon['id']) {
                    $coupons[$ticket['id']] = clone $coupon;
                    $coupons[$ticket['id']]['ticket'] = $ticket;
                    break;
                }
            }
        }
        return $coupons;
    }

    public function getCouponsById($coupon_ids, $with_detail = true)
    {
        if ($with_detail) {
            return Giftcard::with('rules', 'counter')->find($coupon_ids);
        }

        return Giftcard::query()->find($coupon_ids);
    }

    public function get($promotion_id, $with_detail = true)
    {
        $giftcard = $promotion_id instanceof Giftcard ? $promotion_id : Giftcard::query()->findOrFail($promotion_id);

        return $giftcard;
    }
}

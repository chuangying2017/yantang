<?php namespace App\Repositories\Promotion\Giftcard;

use App\Models\Promotion\Giftcard;
use App\Models\Promotion\Ticket;
use App\Services\Promotion\PromotionProtocol;
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
        $tickets = $this->getGiftcardOfUser(access()->id(), PromotionProtocol::STATUS_OF_TICKET_OK, false);

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

    public function getGiftcardOfUser($user_id, $status, $with_coupon = true)
    {
        return $this->query(PromotionProtocol::TYPE_OF_GIFTCARD, $status, $user_id, null, $with_coupon, null);
    }

    protected function query($type, $status = PromotionProtocol::STATUS_OF_TICKET_OK, $user_id = null, $promotion_id = null, $with_promotion = true, $paginate = PromotionProtocol::TICKET_PER_PAGE)
    {
        $query = Ticket::query()->where('type', $type);

        if (($type == PromotionProtocol::TYPE_OF_COUPON) &&  $with_promotion) {
            $query->with('coupon');
        }
        if (($type == PromotionProtocol::TYPE_OF_GIFTCARD) &&  $with_promotion) {
            $query->with('giftcard');
        }

        if ($promotion_id) {
            $query->where('promotion_id', $promotion_id);
        }

        if ($user_id) {
            $query->where('user_id', $user_id);
        }

        if ($status == PromotionProtocol::STATUS_OF_TICKET_OK) {
            $query->effect()->where('status', $status);
        } else if ($status == PromotionProtocol::STATUS_OF_TICKET_EXPIRED) {
            $query->where(function ($query) {
                $query->where('end_time', '<', Carbon::now())->where('status', PromotionProtocol::STATUS_OF_TICKET_OK);
            })->orWhere('status', $status);
        } else {
            $query->where('status', $status);
        }

        $query->orderBy('created_at', 'desc');

        if ($paginate) {
            return $query->paginate($paginate);
        }

        return $query->get();
    }
}

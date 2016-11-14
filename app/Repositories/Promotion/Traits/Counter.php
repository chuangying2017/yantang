<?php namespace App\Repositories\Promotion\Traits;

use App\Models\Promotion\PromotionCounter;
use App\Services\Promotion\PromotionProtocol;

trait Counter {

    public function createCounter($promotion_id, $total, $days = 0)
    {
        return PromotionCounter::create([
            'promotion_id' => $promotion_id,
            'effect_days' => $days,
            'total' => $total,
            'dispatch' => 0,
            'used' => 0
        ]);
    }

    public function updateCounter($promotion_id, $total, $days = 0)
    {
        return PromotionCounter::query()->where('promotion_id', $promotion_id)->update([
            'total' => $total,
            'effect_days' => $days
        ]);
    }

    public function getCounter($promotion_id, $name = null)
    {
        $counter = $promotion_id instanceof PromotionCounter ? $promotion_id : PromotionCounter::query()->findOrFail($promotion_id);
        return !is_null($name) ? $counter[$name] : $counter;
    }

    public function increaseCounter($promotion_id, $name = PromotionProtocol::NAME_OF_COUNTER_DISPATCH, $quantity = 1)
    {
        return PromotionCounter::query()->where('promotion_id', $promotion_id)->increment($name, $quantity);
    }

    public function decreaseCounter($promotion_id, $name = PromotionProtocol::NAME_OF_COUNTER_USED, $quantity = 1)
    {
        return PromotionCounter::query()->where('promotion_id', $promotion_id)->decrement($name, $quantity);
    }

}

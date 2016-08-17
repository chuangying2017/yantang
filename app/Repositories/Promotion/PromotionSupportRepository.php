<?php namespace App\Repositories\Promotion;

use App\Models\Promotion\PromotionCounter;
use App\Models\Promotion\UserPromotion;

class PromotionSupportRepository {

    public static function getUserPromotionTimes($promotion_id, $user_id, $rule_id = null)
    {
        $query = UserPromotion::query()->where('user_id', $user_id)->where('promotion_id', $promotion_id);

        if (!is_null($rule_id)) {
            $query = $query->where('rule_id', $rule_id);
        }

        return $query->get()->count();
    }

    public static function addUserPromotion($user_id, $promotion_id, $rule_id = null, $used = 0)
    {
        PromotionCounter::query()->where('promotion_id', $promotion_id)->increment('dispatch', 1);

        return UserPromotion::create([
            'user_id' => $user_id,
            'promotion_id' => $promotion_id,
            'rule_id' => $rule_id ?: 0,
            'used' => $used,
        ]);
    }

    public static function updateUserPromotionAsUsed($user_id, $promotion_id, $rule_id)
    {
        PromotionCounter::query()->where('promotion_id', $promotion_id)->increment('used', 1);

        return UserPromotion::query()->where('user_id', $user_id)
            ->where('$promotion_id', $promotion_id)
            ->update(['rule_id' => $rule_id, 'used' => 1]);
    }
}

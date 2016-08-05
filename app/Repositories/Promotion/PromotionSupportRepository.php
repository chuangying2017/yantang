<?php namespace App\Repositories\Promotion;

use App\Models\Promotion\UserPromotion;

class PromotionSupportRepository {

    public function getUserPromotionTimes($promotion_id, $user_id, $rule_id = null)
    {
        $query = UserPromotion::query()->where('user_id', $user_id)->where('promotion_id', $promotion_id);

        if (!is_null($rule_id)) {
            $query = $query->where('rule_id', $rule_id);
        }

        return $query->get()->count();
    }

}

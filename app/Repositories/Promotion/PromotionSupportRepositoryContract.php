<?php namespace App\Repositories\Promotion;
interface PromotionSupportRepositoryContract {

    public function getUsefulRules();

    public function getUserPromotionTimes($promotion_id, $user_id, $rule_id = null);

}

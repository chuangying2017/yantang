<?php namespace App\Repositories\Promotion;
interface PromotionSupportRepositoryContract {

    public function getCampaignRules();

    public function getCouponRules($coupon_ids);

    public function getCampaigns();

    public function getCampaign($campaign_id);

    public function getCampaignsByProduct($product_id, $sku_id, $cat_id, $group_id, $brand_id);

    public function getCouponsByProduct($product_id, $sku_id, $cat_id, $group_id, $brand_id);

    public function getCoupons($coupon_ids);

    public function getUserPromotionTimes($promotion_id, $user_id, $rule_id = null);

}

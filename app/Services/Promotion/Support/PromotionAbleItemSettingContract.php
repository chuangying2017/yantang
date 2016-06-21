<?php namespace App\Services\Promotion\Support;

interface PromotionAbleItemSettingContract {

    public function setGifts($item_key = null);
    public function unsetGifts($item_key = null);
    public function getGifts($item_key = null);

    public function setCredits();
    public function unsetCredits();
    public function getCredits();


    public function setSkusDiscountAmount($item_keys, $discount_amount);
    public function unsetSkusDiscountAmount($item_keys, $discount_amount);
    public function getSkusDiscountAmount($item_keys);


    public function setSpecialPrice($special_price, $max_quantity = null, $qualify_text = null);
    public function unsetSpecialPrice($special_price, $max_quantity = null, $qualify_text = null);
    public function getSpecialPrice();

    public function setDiscountAmount($discount_amount);
    public function unsetDiscountAmount($discount_amount);
    public function getDiscountAmount();


    public function setDiscountExpressFee($discount_express_fee);
    public function unsetDiscountExpressFee($discount_express_fee);

    public function setRelateCoupons($rules);
    public function unsetRelateCoupon($rule_key = null);
    public function getRelateCoupons();

    public function setRelateCampaigns($rules);
    public function unsetRelateCampaign($rule_key = null);
    public function getRelateCampaigns();

    public function setUsableCampaigns($rule_key);
    public function unsetUsableCampaign($rule_key = null);
    public function getUsableCampaigns();

    public function setUsableCoupons($rule_key);
    public function unsetUsableCoupon($rule_key = null);
    public function getUsableCoupons();

    public function setCampaignBenefit($rule_key, $discount, $benefit);
    public function unsetCampaignBenefit($rule_key = null);

    public function setCouponBenefit($rule_key, $discount, $benefit);
    public function unsetCouponBenefit($rule_key = null);

}

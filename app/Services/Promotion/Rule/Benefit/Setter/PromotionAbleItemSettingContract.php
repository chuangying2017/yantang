<?php namespace App\Services\Promotion\Rule\Benefit\Setter;

interface PromotionAbleItemSettingContract {

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

    public function addCampaign($rule_key, $discount, $benefit);

    public function removeCampaign($rule_key = null);

    public function addCoupon($rule_key, $discount, $benefit);

    public function removeCoupon($rule_key = null);

}

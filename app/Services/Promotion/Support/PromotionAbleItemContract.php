<?php namespace App\Services\Promotion\Support;

interface PromotionAbleItemContract extends PromotionAbleItemBenefitContract {


    public function getItems($item_keys = null);

    public function getAmount($item_keys = null);

    public function getItemsQuantity($item_keys = null);

    public function getExpressFee();

    public function getDiscountAmount();

    public function getItemProductId($item_key);

    public function getItemProductSkuID($item_key);

    public function getItemCategory($item_key);

    public function getItemGroup($item_key);
    
    public function getItemBrand($item_key);

    public function setRules($rules);

    public function getRules();

    public function getSkuPriceTag();

}

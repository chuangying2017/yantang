<?php namespace App\Services\Promotion\Support;

interface PromotionAbleItemContract extends PromotionAbleItemBenefitContract {

    public function init($items_data);

    public function getItems();

    public function getAmount($item_keys = null);

    public function getItemsQuantity($item_keys = null);

    public function getExpressFee();

    public function getDiscountAmount();

    public function getItemProductId($item_key);

    public function getItemProductSkuID($item_key);

    public function getItemCategory($item_key);

    public function getItemGroup($item_key);

    public function setRules($rules);

    public function getRules();

}

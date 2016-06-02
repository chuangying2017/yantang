<?php namespace App\Services\Promotion\Data;

use App\Services\Promotion\Data\Traits\PromotionAmountData;
use App\Services\Promotion\Data\Traits\PromotionExpressFeeData;
use App\Services\Promotion\Data\Traits\PromotionItemsData;
use App\Services\Promotion\Data\Traits\PromotionRuleData;
use App\Services\Promotion\Data\Traits\PromotionUserData;

Class PromotionData {

    use PromotionRuleData,
        PromotionItemsData,
        PromotionAmountData,
        PromotionUserData,
        PromotionExpressFeeData;

    protected $discount_amount = 0;
    protected $amount = 0;
    protected $express_fee = 0;
    protected $discount_express_fee = 0;

    public function initPromotionData($user, $items, $rules)
    {
        $this->setUser($user);
        $this->setItems($items);
        $this->setAmount($this->sumItemsAmount());
        $this->setExpressFee();
        $this->setRules($rules);
    }

    public function getPromotionData()
    {
        return [
            'amount' => $this->amount,
            'discount_amount' => $this->discount_amount,
            'items' => $this->items,
            'express_fee' => $this->express_fee,
            'discount_express_fee' => $this->discount_express_fee,
            'user' => $this->user,
            'rules' => $this->rules
        ];
    }

}

<?php namespace App\Services\Promotion\Data;

use App\Services\Promotion\Data\Traits\PromotionAmountData;
use App\Services\Promotion\Data\Traits\PromotionBenefitData;
use App\Services\Promotion\Data\Traits\PromotionExpressFeeData;
use App\Services\Promotion\Data\Traits\PromotionItemsData;
use App\Services\Promotion\Data\Traits\PromotionRuleData;
use App\Services\Promotion\Data\Traits\PromotionUserData;

Class PromotionData {

    use PromotionRuleData,
        PromotionBenefitData,
        PromotionItemsData,
        PromotionUserData;


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

<?php namespace App\Services\Promotion\Data\BenefitObject;
class PromotionDataBenefitObjectProduct extends PromotionDataBenefitObjectAbstract {

    public function getBenefitObject()
    {
        return $this->data->getRuleUsageItems($this->rule_key);
    }

    public function setBenefitObject($benefit_value)
    {
        $this->setRuleBenefit($benefit_value);
        foreach ($benefit_value as $gift_item) {
            $this->data->addFreeItems($gift_item['product_sku_id'], $gift_item['quantity']);
        }
    }

}

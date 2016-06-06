<?php namespace App\Services\Promotion\Data\BenefitObject;
class PromotionDataBenefitObjectSpecialPrice extends PromotionDataBenefitObjectAbstract {

    public function getBenefitObject()
    {
        return $this->data->getRuleUsageItems($this->rule_key);
    }

    public function setBenefitObject($benefit_value)
    {
        $this->setRuleBenefit($benefit_value);
        $items = $this->data->getRuleUsageItems($this->rule_key);
        foreach ($items as $item_key => $item) {
            $this->data->setItemsRuleBenefit($item_key, $this->rule_key, $benefit_value, true);
        }
    }

}

<?php namespace App\Services\Promotion\Data\BenefitObject;
class PromotionDataBenefitObjectCredits extends PromotionDataBenefitObjectAbstract {

    public function getBenefitObject()
    {
        return $this->data->getRuleUsageItems($this->rule_key);
    }

    public function setBenefitObject($benefit_value)
    {
        $this->setRuleBenefit($benefit_value);
    }

}

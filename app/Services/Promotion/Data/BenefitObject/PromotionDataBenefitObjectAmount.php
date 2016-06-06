<?php namespace App\Services\Promotion\Data\BenefitObject;
class PromotionDataBenefitObjectAmount extends PromotionDataBenefitObjectAbstract {

    public function getBenefitObject()
    {
        return $this->data->getAmount();
    }

    public function setBenefitObject($benefit_value)
    {
        $this->setRuleBenefit($benefit_value);
        return $this->data->addDiscountAmount($benefit_value);
    }

}

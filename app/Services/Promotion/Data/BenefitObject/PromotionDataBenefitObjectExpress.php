<?php namespace App\Services\Promotion\Data\BenefitObject;
class PromotionDataBenefitObjectExpress extends PromotionDataBenefitObjectAbstract {

    public function getBenefitObject()
    {
        return $this->data->getExpressFee();
    }

    public function setBenefitObject($benefit_value)
    {
        $this->setRuleBenefit($benefit_value);
        return $this->data->addDiscountExpressFee($benefit_value);
    }

}

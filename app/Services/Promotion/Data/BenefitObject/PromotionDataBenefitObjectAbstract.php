<?php namespace App\Services\Promotion\Data\BenefitObject;

use App\Services\Promotion\Data\PromotionData;

abstract class PromotionDataBenefitObjectAbstract implements PromotionDataBenefitObjectInterface {

    /**
     * @var PromotionData
     */
    protected $data;
    /**
     * @var
     */
    protected $rule_key;

    /**
     * PromotionDataBenefitObjectInterface constructor.
     * @param PromotionData $data
     */
    public function __construct(PromotionData $data, $rule_key)
    {
        $this->data = $data;

        $this->rule_key = $rule_key;
    }

    public function setRuleBenefit($benefit_value)
    {
        $this->data->setRuleUsageBenefit($this->rule_key, $benefit_value);
    }

}

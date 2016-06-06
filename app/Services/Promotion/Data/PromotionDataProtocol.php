<?php namespace App\Services\Promotion\Data;

use App\Services\Promotion\Data\BenefitObject\PromotionDataBenefitObjectAmount;
use App\Services\Promotion\Data\BenefitObject\PromotionDataBenefitObjectCredits;
use App\Services\Promotion\Data\BenefitObject\PromotionDataBenefitObjectExpress;
use App\Services\Promotion\Data\BenefitObject\PromotionDataBenefitObjectProduct;
use App\Services\Promotion\Data\BenefitObject\PromotionDataBenefitObjectSpecialPrice;
use App\Services\Promotion\PromotionProtocol;

class PromotionDataProtocol {

    public static function getBenefitObjectHandler($rule_discount_type, PromotionData $data, $rule_key)
    {
        $handler = null;
        switch ($rule_discount_type) {
            case PromotionProtocol::DISCOUNT_TYPE_OF_AMOUNT:
                $handler = new PromotionDataBenefitObjectAmount($data, $rule_key);
                break;
            case PromotionProtocol::DISCOUNT_TYPE_OF_EXPRESS:
                $handler = new PromotionDataBenefitObjectExpress($data, $rule_key);
                break;
            case PromotionProtocol::DISCOUNT_TYPE_OF_SPECIAL_PRICE:
                $handler = new PromotionDataBenefitObjectSpecialPrice($data, $rule_key);
                break;
            case PromotionProtocol::DISCOUNT_TYPE_OF_CREDITS:
                $handler = new PromotionDataBenefitObjectCredits($data, $rule_key);
                break;
            case PromotionProtocol::DISCOUNT_TYPE_OF_PRODUCT:
                $handler = new PromotionDataBenefitObjectProduct($data, $rule_key);
                break;
            default:
                throw new \Exception('优惠资源计算类型错误');
        }
        return $handler;
    }

}

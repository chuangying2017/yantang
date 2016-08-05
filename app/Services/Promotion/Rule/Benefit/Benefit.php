<?php namespace App\Services\Promotion\Rule\Benefit;

use App\Services\Promotion\PromotionProtocol;
use App\Services\Promotion\Rule\Benefit\Setter\PromotionAbleItemBenefitContract;
use App\Services\Promotion\Support\PromotionAbleItemContract;

abstract class Benefit {

    /**
     * @var PromotionAbleItemBenefitContract
     */
    protected $benefit_setter;

    public function __construct(PromotionAbleItemBenefitContract $benefit_setter)
    {
        $this->benefit_setter = $benefit_setter;
    }

    public abstract function calAndSet($mode, $value, PromotionAbleItemContract $items, $item_option = null);

    public abstract function rollback($mode, $benefit_value, PromotionAbleItemContract $items, $item_option = null);

    protected static function calModeValue($mode, $origin_value, $cal_value)
    {
        $result = $origin_value;
        switch ($mode) {
            case PromotionProtocol::DISCOUNT_MODE_OF_DECREASE:
                $result = bcsub($origin_value, $cal_value, 0);
                break;
            case PromotionProtocol::DISCOUNT_MODE_OF_PERCENTAGE:
                $result = bcmul($origin_value, (100 - $cal_value), 0);
                break;
            case PromotionProtocol::DISCOUNT_MODE_OF_EQUAL:
                $result = $cal_value;
                break;
            case PromotionProtocol::DISCOUNT_MODE_OF_MUL:
                $result = bcmul($origin_value, $cal_value, 0);
                break;
            default:
                throw new \Exception('优惠模式错误');
        }
        return $result;
    }
}

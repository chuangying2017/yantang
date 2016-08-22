<?php namespace App\Services\Promotion\Rule\Benefit\Setter;

use App\Services\Promotion\PromotionProtocol;
use App\Services\Promotion\Support\PromotionAbleItemContract;

class PromotionAmount implements PromotionAbleItemBenefitContract {

    /**
     * @var PromotionAbleItemContract
     */
    protected $items;

    public function init($benefit_name)
    {
        $this->items = $benefit_name;
        return $this;
    }

    public function add($benefit, $key = null)
    {
        $this->items->setDiscountAmount($benefit, PromotionProtocol::ACTION_OF_ADD);
        return $this;
    }

    public function remove($benefit, $key = null)
    {
        $this->items->setDiscountAmount($benefit, PromotionProtocol::ACTION_OF_SUB);
        return $this;
    }

    public function get($key = null)
    {
        return $this->items->getDiscountAmount();
    }
}

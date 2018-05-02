<?php namespace App\Services\Promotion\Rule\Benefit\Setter;

use App\Services\Promotion\PromotionProtocol;
use App\Services\Promotion\Support\PromotionAbleItemContract;

class PromotionAmount implements PromotionAbleItemBenefitContract {

    /**
     * @var PromotionAbleItemContract
     */
    protected $order;

    protected $related_skus;

    public function init($benefit_name, $related_skus = null)
    {
        $this->related_skus = $related_skus;
        $this->order = $benefit_name;
        return $this;
    }

    public function add($discount_amount)
    {
        return $this->setProductDiscount($discount_amount, PromotionProtocol::ACTION_OF_ADD);
    }

    public function remove($discount_amount)
    {
        return $this->setProductDiscount($discount_amount, PromotionProtocol::ACTION_OF_SUB);
    }

    protected function setProductDiscount($discount_amount, $action)
    {
        $unset_amount = $discount_amount;
        $base_total = array_sum(array_pluck($this->related_skus, 'origin_total_amount'));

        $sku_counter = 0;

        foreach ($this->related_skus as $related_sku) {

            $sku_discount_amount = intval(round($discount_amount / $base_total * $related_sku['origin_total_amount']));

            //检查有单品优惠金额是否超过总优惠金额
            $sku_discount_amount = $unset_amount > $sku_discount_amount ? $sku_discount_amount : $unset_amount;
            $unset_amount -= $sku_discount_amount;

            ++$sku_counter;
            if ($sku_counter == count($this->related_skus) && $unset_amount > 0) {
                $sku_discount_amount += $unset_amount;
            }

            $this->order->setProductDiscount($related_sku['id'], $sku_discount_amount, $action);
        }

        return $this;
    }

    public function get()
    {
        return $this->order->getDiscountAmount();
    }

}

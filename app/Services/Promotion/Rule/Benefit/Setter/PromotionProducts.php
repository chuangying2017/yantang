<?php namespace App\Services\Promotion\Rule\Benefit\Setter;

use App\Services\Promotion\PromotionProtocol;
use App\Services\Promotion\Support\PromotionAbleItemContract;

class PromotionProducts implements PromotionAbleItemBenefitContract {

    /**
     * @var PromotionAbleItemContract
     */
    protected $order;

    protected $related_skus;

    /**
     * @param $benefit_name
     * @param null $related_skus
     * @return $this
     */
    public function init($benefit_name, $related_skus = null)
    {
        $this->related_skus = $related_skus;
        $this->order = $benefit_name;
        return $this;
    }

    public function add($skus)
    {
        foreach ($skus as $sku) {
            $this->order->setPromotionProducts($sku, PromotionProtocol::ACTION_OF_ADD);
        }
    }

    public function remove($skus)
    {
        foreach ($skus as $sku) {
            $this->order->setPromotionProducts($sku, PromotionProtocol::ACTION_OF_SUB);
        }
    }

    public function get()
    {
        // TODO: Implement get() method.
    }


}

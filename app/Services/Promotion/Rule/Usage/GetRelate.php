<?php namespace App\Services\Promotion\Rule\Usage;

use App\Services\Promotion\PromotionProtocol;
use App\Services\Promotion\Support\PromotionAbleItemContract;

class GetRelate {

    /**
     * @var Usage
     */
    protected $filter;

    public function setRelateType($type)
    {
        $filters = [
            PromotionProtocol::ITEM_TYPE_OF_ALL => AllItemsUsage::class,
            PromotionProtocol::ITEM_TYPE_OF_PRODUCT => ProductUsage::class,
            PromotionProtocol::ITEM_TYPE_OF_SKU => SkuUsage::class,
            PromotionProtocol::ITEM_TYPE_OF_CATEGORY => CategoryUsage::class,
            PromotionProtocol::ITEM_TYPE_OF_GROUP => '',
            PromotionProtocol::ITEM_TYPE_OF_BRAND => '',
        ];

        $handler = array_get($filters, $type, null);
        if (is_null($handler)) {
            throw new \Exception('错误的优惠商品关联类型');
        }

        $this->setRelateFilter(app()->make($handler));

        return $this;
    }

    protected function setRelateFilter(Usage $filter)
    {
        $this->filter = $filter;
    }

    public function filter(PromotionAbleItemContract $items, $rule_items)
    {
        return $this->filter->filter($items, $rule_items);
    }
}

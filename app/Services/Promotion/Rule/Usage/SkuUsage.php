<?php namespace App\Services\Promotion\Rule\Usage;
use App\Services\Promotion\Support\PromotionAbleItemContract;

class SkuUsage implements Usage{

    public function filter(PromotionAbleItemContract $items, $item_values)
    {
        $item_keys = [];
        foreach ($items->getItems() as $key => $item) {
            if (in_array($item_values, $item['id'])) {
                $item_keys[] = $key;
            }
        }

        return $item_keys;
    }
}

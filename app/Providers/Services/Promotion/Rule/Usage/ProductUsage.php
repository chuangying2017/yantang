<?php namespace App\Services\Promotion\Rule\Usage;

use App\Services\Promotion\Support\PromotionAbleItemContract;

class ProductUsage implements Usage {

    public function filter(PromotionAbleItemContract $items, $item_values)
    {
        $item_keys = [];
        foreach ($items->getItems() as $key => $item) {
            if (in_array($item['product_id'], $item_values)) {
                $item_keys[] = $key;
            }
        }

        return $item_keys;
    }
}

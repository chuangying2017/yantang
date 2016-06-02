<?php namespace App\Services\Promotion\Rule\Usage;

class AllItemsUsage implements Usage {

    public function filter($items, $item_values)
    {
        return $items;
    }
}

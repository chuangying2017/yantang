<?php namespace App\Services\Promotion\Rule\Usage;

class AllItemsUsage implements Usage {

    public function filter($items, $item_values)
    {
        $item_id = [];
        foreach($items as $item) {
            $item_id[] = $item['id'];
        }
        return $item_id;
    }
}

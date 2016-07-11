<?php namespace App\Repositories\Preorder\Product;

use App\Models\Subscribe\PreorderSkuCounter;

class PreorderSkuCounterRepository implements PreorderSkuCounterRepoContract {

    public function decrementCounter($preorder_id, $product_sku_id, $quantity)
    {
        $counter = $this->getCounter($preorder_id, $product_sku_id);
        if ($counter->remain > $quantity) {
            $counter->remain -= $quantity;
            $counter->save();
            return $counter;
        }

        throw new \Exception('产品不足');
    }

    public function getCounter($preorder_id, $product_sku_id)
    {
        return PreorderSkuCounter::query()->where('preorder_id', $preorder_id)->where('product_sku_id', $product_sku_id)->firstOrFail();
    }

    public function createCounter($preorder_id, $data)
    {
        $counters = [];
        foreach ($data as $key => $counter) {
            $counter['preorder_id'] = $preorder_id;
            $counters[] = PreorderSkuCounter::create(array_only($counter, [
                'order_id',
                'order_sku_id',
                'product_id',
                'product_sku_id',
                'total',
                'remain',
                'per_day'
            ]));
        }
        return $counters;
    }
}

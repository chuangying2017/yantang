<?php namespace App\Repositories\Preorder\Product;

interface PreorderSkuCounterRepoContract {

    public function getCounter($preorder_id, $product_sku_id);

    public function decrementCounter($preorder_id, $product_sku_id, $quantity);

    public function createCounter($preorder_id, $data);
}

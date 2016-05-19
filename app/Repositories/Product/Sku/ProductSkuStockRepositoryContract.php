<?php namespace App\Repositories\Product\Sku;

interface ProductSkuStockRepositoryContract {

    public function increaseStock($product_sku_id, $quantity = 1);

    public function decreaseStock($product_sku_id, $quantity = 1);

    public function getStock($product_sku_ids);


}

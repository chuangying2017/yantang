<?php namespace App\Repositories\Product\Sku;

interface ProductSkuRepositoryContract {

    public function createSku($sku_data, $product_id);

    public function updateSku($product_sku_id, $sku_data);

    public function deleteSku($product_sku_id);

    public function deleteSkusOfProduct($product_id);

    public function updateSkusOfProduct($product_id, $new_sku_data);

}

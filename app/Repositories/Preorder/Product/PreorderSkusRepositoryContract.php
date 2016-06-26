<?php namespace App\Repositories\Preorder\Product;

interface PreorderSkusRepositoryContract {

    public function getAll($order_id);

    public function createPreorderProducts($order_id, $product_skus);

    public function deletePreorderProducts($order_id);

}

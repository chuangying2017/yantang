<?php namespace App\Repositories\Product;

use App\Repositories\Search\SearchableContract;

interface ProductRepositoryContract extends SearchableContract{

    public function createProduct($product_data);

    public function updateProduct($product_id, $product_data);

    public function getProduct($product_id);

    public function getAllProducts($order_by = 'created_at', $sort = 'desc', $status, $brand = null, $cat = null);

    public function getProductsPaginated($order_by = 'created_at', $sort = 'desc', $status, $brand = null, $cat = null, $per_page = ProductProtocol::PRODUCT_PER_PAGE);

    public function deleteProduct($product_id);

}

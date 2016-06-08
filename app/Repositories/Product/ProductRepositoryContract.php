<?php namespace App\Repositories\Product;

use App\Repositories\Search\SearchableContract;

interface ProductRepositoryContract extends SearchableContract {

    public function createProduct($product_data);

    public function updateProduct($product_id, $product_data);

    public function getProduct($product_id, $with_detail = true);

    public function getAllProducts($brand = null, $cat = null, $group = null, $order_by = 'created_at', $sort = 'desc', $status = ProductProtocol::VAR_PRODUCT_STATUS_UP);

    public function getProductsPaginated($brand = null, $cat = null, $group = null, $order_by = 'created_at', $sort = 'desc', $status = ProductProtocol::VAR_PRODUCT_STATUS_UP, $per_page = ProductProtocol::PRODUCT_PER_PAGE);

    public function deleteProduct($product_id);


}

<?php namespace App\Repositories\Product;

use App\Repositories\Search\SearchableContract;

interface ProductRepositoryContract extends SearchableContract {

    public function createProduct($product_data);

    public function updateProduct($product_id, $product_data);

    public function updateProductAsUp($product_id);

    public function updateProductAsDown($product_id);

    public function getProduct($product_id, $with_detail = true);

    public function getAllProducts($brand = null, $cat = null, $group = null, $type = null, $status = ProductProtocol::VAR_PRODUCT_STATUS_UP, $order_by = 'created_at', $sort = 'desc');

    public function getProductsPaginated($brand = null, $cat = null, $group = null, $type = null, $status = ProductProtocol::VAR_PRODUCT_STATUS_UP, $order_by = 'created_at', $sort = 'desc', $per_page = ProductProtocol::PRODUCT_PER_PAGE);

    public function deleteProduct($product_id);


}

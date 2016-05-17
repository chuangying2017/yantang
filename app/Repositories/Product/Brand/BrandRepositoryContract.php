<?php namespace App\Repositories\Product;

interface BrandRepositoryContract {

    public function createBrand($data);

    public function updateBrand($data);

    public function getAllBrands($order_by = 'product_count', $sort = 'desc');

    public function deleteBrand($brand_id);

}

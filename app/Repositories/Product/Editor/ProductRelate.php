<?php namespace App\Repositories\Product\Editor;

use App\Models\Product\Product;

class ProductRelate extends ProductEditor {


    public function handle(array $product_data, Product $product)
    {
        $cat_ids = array_get($product, 'group_ids', []);
        array_push($cat_ids, $product_data['cat_id']);

        $product->cats()->sync($cat_ids);

        return $this->next($product_data, $product);
    }
}

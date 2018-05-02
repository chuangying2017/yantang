<?php namespace App\Repositories\Product\Editor;

use App\Models\Product\Product;

class SetProductSku extends EditorAbstract {

    public function handle(array $product_data, Product $product)
    {
        if ($this->singleSku($product_data)) {
            foreach ($product_data['skus'] as $key => $sku_data) {
                $product_data['skus'][$key]['name'] = array_get($sku_data, 'name', $product_data['title']);
                $product_data['skus'][$key]['cover_image'] = array_get($sku_data, 'cover_image', $product_data['cover_image']);
            }
        }

        return $this->next($product_data, $product);
    }

    private function singleSku($product_data)
    {
        return count($product_data['skus']) == 1;
    }

}

<?php namespace App\Repositories\Product\Editor;

use App\Models\Product\Product;
use App\Repositories\Product\ProductProtocol;

class SetStatus extends EditorAbstract {

    public function handle(array $product_data, Product $product)
    {
       // $product_data['status'] = ProductProtocol::VAR_PRODUCT_STATUS_DOWN; 根据前台传值

        return $this->next($product_data, $product);
    }
}

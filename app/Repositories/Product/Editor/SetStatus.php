<?php namespace App\Repositories\Product\Editor;

use App\Models\Product\Product;
use App\Repositories\Product\ProductProtocol;

class SetStatus extends ProductEditor {

    public function handle(array $product_data, Product $product)
    {
        $product_data['status'] = ProductProtocol::VAR_PRODUCT_STATUS_UP;

        return $this->next($product_data, $product);
    }
}

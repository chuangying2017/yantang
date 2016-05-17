<?php namespace App\Repositories\Product\Editor;

use App\Models\Product\Product;
use App\Models\Product\ProductInfo;

class AddInfo extends ProductEditor {

    public function handle(array $product_data, Product $product)
    {
        $product->info()->create([
            'attr' => $product_data['attr'],
            'tags' => $product_data['tags'],
            'detail' => $product_data['detail'],
        ]);

        return $this->next($product_data, $product);
    }


}

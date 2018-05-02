<?php namespace App\Repositories\Product\Editor;

use App\Models\Product\Product;

class UpdateImages extends EditorAbstract {

    public function handle(array $product_data, Product $product)
    {
        $product->images()->sync($product_data['image_ids']);

        return $this->next($product_data, $product);
    }
}

<?php namespace App\Repositories\Product\Editor;

use App\Models\Product\Product;
use App\Repositories\Category\CategoryProtocol;

class RelateProduct extends EditorAbstract {


    public function handle(array $product_data, Product $product)
    {
        $product->groups()->sync(
            array_merge(
                to_array($product_data['cat_id']),
                array_get($product, 'group_ids', [])
            )
        );

        return $this->next($product_data, $product);
    }
}

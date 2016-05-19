<?php namespace App\Repositories\Product\Editor;

use App\Models\Product\Product;
use App\Repositories\Category\CategoryProtocol;

class RelateProduct extends EditorAbstract {


    public function handle(array $product_data, Product $product)
    {
        $product->brand()->sync(array_get($product, 'group_ids', []), ['type' => CategoryProtocol::TYPE_OF_GROUP]);

        $product->cats()->sync($product_data['cat_id'], ['type' => CategoryProtocol::TYPE_OF_MAIN]);

        return $this->next($product_data, $product);
    }
}
